<?php

namespace Modules\OrgPortal\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\OrgPortal\Models\Organization;
use Modules\OrgPortal\Models\OrganizationMember;

define('ORGPORTAL_MODULE', 'orgportal');

class OrgPortalServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerTranslations();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->registerMenuHooks();
        $this->registerCustomerHooks();
        $this->registerConversationHooks();
        $this->registerSearchHooks();
        $this->registerEupHooks();
        $this->registerNotificationHooks();
    }

    public function register()
    {
        //
    }

    // -------------------------------------------------------------------------
    // Hooks
    // -------------------------------------------------------------------------

    protected function registerMenuHooks()
    {
        // Add "Organizations" item to Manage menu
        \Eventy::addAction('menu.manage.append', function () {
            echo '<li><a href="' . route('orgportal.admin.index') . '">'
                . __('orgportal::messages.organizations') . '</a></li>';
        }, 20, 0);
    }

    protected function registerCustomerHooks()
    {
        // Inject organization selector into the customer edit form
        \Eventy::addAction('customer.edit.after_fields', function ($customer, $errors) {
            $organizations   = \Modules\OrgPortal\Models\Organization::orderBy('name')->get();
            $currentMember   = \Modules\OrgPortal\Models\OrganizationMember::where('customer_id', $customer->id)->first();
            $currentOrgId    = $currentMember ? $currentMember->organization_id : null;
            $currentRole     = $currentMember ? $currentMember->role : 'member';

            echo view('orgportal::admin.partials.customer_org_field', [
                'organizations' => $organizations,
                'currentOrgId'  => $currentOrgId,
                'currentRole'   => $currentRole,
                'errors'        => $errors,
            ])->render();
        }, 20, 2);

        // Save organization assignment when customer form is submitted
        \Eventy::addAction('customer.updated', function ($customer) {
            $orgId = request()->input('orgportal_organization_id');
            $role  = request()->input('orgportal_role', 'member');

            if (!in_array($role, ['member', 'manager'])) {
                $role = 'member';
            }

            $existing = \Modules\OrgPortal\Models\OrganizationMember::where('customer_id', $customer->id)->first();

            // "— None —" selected: remove from any org
            if (!$orgId) {
                if ($existing) {
                    $existing->delete();
                }
                return;
            }

            // Verify the organization exists
            if (!\Modules\OrgPortal\Models\Organization::where('id', $orgId)->exists()) {
                return;
            }

            if ($existing) {
                // Update org / role
                $existing->organization_id = $orgId;
                $existing->role            = $role;
                $existing->save();
            } else {
                \Modules\OrgPortal\Models\OrganizationMember::create([
                    'organization_id' => $orgId,
                    'customer_id'     => $customer->id,
                    'role'            => $role,
                ]);
            }
        }, 20, 1);
    }

    protected function registerConversationHooks()
    {
        // Render org badge next to tags in the conversation subject area
        \Eventy::addAction('conversation.after_subject', function ($conversation, $mailbox) {
            if (!$conversation->customer_id) {
                return;
            }

            $member = OrganizationMember::where('customer_id', $conversation->customer_id)
                ->with('organization')
                ->first();

            if (!$member || !$member->organization) {
                return;
            }

            $searchUrl = route('conversations.search') . '?f[organization]=' . $member->organization_id;

            echo view('orgportal::partials.org_badge', [
                'organization' => $member->organization,
                'searchUrl'    => $searchUrl,
            ])->render();
        }, 20, 2);
    }

    protected function registerSearchHooks()
    {
        // Inject organization dropdown into the search filters panel
        \Eventy::addAction('search.display_filters', function ($filters) {
            $organizations = Organization::orderBy('name')->get(['id', 'name']);
            $currentOrgId  = $filters['organization'] ?? null;

            echo view('orgportal::partials.search_org_filter', [
                'organizations' => $organizations,
                'currentOrgId'  => $currentOrgId,
            ])->render();
        }, 20, 1);

        // Filter search results to conversations of org members
        \Eventy::addFilter('search.conversations.apply_filters', function ($query, $filters, $q) {
            if (empty($filters['organization'])) {
                return $query;
            }

            $orgId = (int) $filters['organization'];

            $memberIds = OrganizationMember::where('organization_id', $orgId)
                ->pluck('customer_id');

            if ($memberIds->isEmpty()) {
                // Org exists but has no members — return zero results
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('conversations.customer_id', $memberIds);
            }

            return $query;
        }, 20, 3);
    }

    protected function registerEupHooks()
    {
        // EUP has no tab/settings hooks — inject nav links via JS on layout.body_bottom.
        // Fires on every EUP page; skipped for non-EUP pages and non-managers.
        \Eventy::addAction('layout.body_bottom', function () {
            if (!\EndUserPortal::isEup()) {
                return;
            }

            $customer = \EndUserPortal::authCustomer();
            if (!$customer) {
                return;
            }

            $member = OrganizationMember::where('customer_id', $customer->id)
                ->where('role', 'manager')
                ->first();

            if (!$member) {
                return;
            }

            $mailboxId = request()->route('mailbox_id');
            if (!$mailboxId) {
                return;
            }

            echo view('orgportal::partials.eup_nav_inject', [
                'mailbox_id' => $mailboxId,
            ])->render();
        }, 20, 0);
    }

    protected function registerNotificationHooks()
    {
        // Fire email notifications to org managers when a customer creates a new conversation.
        // Hook: conversation.created_by_customer fires with ($conversation, $thread, $customer).
        \Eventy::addAction('conversation.created_by_customer', function ($conversation, $thread, $customer) {
            if (!$customer || !$customer->id) {
                return;
            }

            $authorMember = OrganizationMember::where('customer_id', $customer->id)->first();

            if (!$authorMember) {
                return;
            }

            $managers = OrganizationMember::where('organization_id', $authorMember->organization_id)
                ->where('role', 'manager')
                ->where('notify_on_new_ticket', true)
                ->where('customer_id', '!=', $customer->id)
                ->with('customer')
                ->get();

            foreach ($managers as $manager) {
                if (!$manager->customer) {
                    continue;
                }
                $email = $this->getCustomerEmail($manager->customer);
                if ($email) {
                    try {
                        \Mail::to($email)->send(
                            new \Modules\OrgPortal\Mail\OrgNewTicketMail(
                                $manager->customer,
                                $customer,
                                $conversation
                            )
                        );
                    } catch (\Exception $e) {
                        \Helper::logException($e);
                    }
                }
            }
        }, 20, 3);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    protected function getCustomerEmail($customer): ?string
    {
        if (!$customer) {
            return null;
        }
        // Customer has no $email property — use getMainEmail() or emails() relation
        if (method_exists($customer, 'getMainEmail')) {
            $email = $customer->getMainEmail();
            if ($email) {
                return $email;
            }
        }
        $emailModel = $customer->emails()->first();
        return $emailModel ? $emailModel->email : null;
    }

    // -------------------------------------------------------------------------
    // Registration helpers
    // -------------------------------------------------------------------------

    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('orgportal.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'orgportal');
    }

    public function registerViews()
    {
        $viewPath   = resource_path('views/modules/orgportal');
        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([$sourcePath => $viewPath], 'views');

        $this->loadViewsFrom(
            array_merge(
                array_map(fn ($p) => $p . '/modules/orgportal', \Config::get('view.paths')),
                [$sourcePath]
            ),
            'orgportal'
        );
    }

    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/orgportal');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'orgportal');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'orgportal');
        }
    }

    public function provides()
    {
        return [];
    }
}
