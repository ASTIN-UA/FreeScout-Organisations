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
                . __('Organizations') . '</a></li>';
        }, 20, 0);
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
                    'title' => __('Company Tickets'),
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
        // Fire email notifications to org managers when a new conversation is created
        \Eventy::addAction('conversation.created', function ($conversation) {
            if (!$conversation || !$conversation->customer_id) {
                return;
            }

            $authorMember = OrganizationMember::where('customer_id', $conversation->customer_id)
                ->first();

            if (!$authorMember) {
                return;
            }

            $managers = OrganizationMember::where('organization_id', $authorMember->organization_id)
                ->where('role', 'manager')
                ->where('notify_on_new_ticket', true)
                ->where('customer_id', '!=', $conversation->customer_id)
                ->with('customer')
                ->get();

            foreach ($managers as $manager) {
                $email = $this->getCustomerEmail($manager->customer);
                if ($email) {
                    try {
                        \Mail::to($email)->send(
                            new \Modules\OrgPortal\Mail\OrgNewTicketMail(
                                $manager->customer,
                                $authorMember->customer,
                                $conversation
                            )
                        );
                    } catch (\Exception $e) {
                        \Helper::logException($e);
                    }
                }
            }
        }, 20, 1);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    protected function getCustomerEmail($customer): ?string
    {
        if (!$customer) {
            return null;
        }
        // Customer may expose $customer->email directly or via emails() relation
        if (!empty($customer->email)) {
            return $customer->email;
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
