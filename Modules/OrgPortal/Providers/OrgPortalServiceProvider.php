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

    protected function registerEupHooks()
    {
        // Inject "Company Tickets" tab link into EUP tickets page
        // Hook name: verified against EndUserPortal module (see eup tickets.blade.php)
        \Eventy::addFilter('eup.tickets.tabs', function ($tabs) {
            $customerId = \Session::get('eup_customer_id');
            if (!$customerId) {
                return $tabs;
            }

            $member = OrganizationMember::where('customer_id', $customerId)
                ->where('role', 'manager')
                ->first();

            if ($member) {
                $tabs[] = [
                    'id'    => 'company-tickets',
                    'title' => __('orgportal::messages.company_tickets'),
                    'url'   => route('orgportal.portal.company-tickets'),
                ];
            }

            return $tabs;
        }, 20, 1);

        // Inject manager settings section into EUP settings page
        \Eventy::addAction('eup.settings.after', function () {
            $customerId = \Session::get('eup_customer_id');
            if (!$customerId) {
                return;
            }

            $member = OrganizationMember::where('customer_id', $customerId)
                ->where('role', 'manager')
                ->first();

            if ($member) {
                echo view('orgportal::portal.settings_inline', [
                    'member' => $member,
                ])->render();
            }
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
