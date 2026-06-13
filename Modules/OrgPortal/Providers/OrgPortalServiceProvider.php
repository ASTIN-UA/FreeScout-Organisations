<?php

namespace Modules\OrgPortal\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\OrgPortal\Models\Organization;
use Modules\OrgPortal\Models\OrganizationMember;
use Modules\OrgPortal\Models\OrgNotificationSubscription;
use Modules\OrgPortal\Models\OrgPortalNotification;

define('ORGPORTAL_MODULE', 'orgportal');

class OrgPortalServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Global user permission ID for managing organizations.
     * Chosen well above FreeScout's built-in range (1-10) to avoid collisions.
     * FreeScout saves/reads this automatically because we register it via the
     * `user_permissions.list` and `user_permissions.name` Eventy filters.
     */
    const PERM_MANAGE_ORGANIZATIONS = 100;
    const PERM_MANAGE_TEMPLATES     = 101;

    public function boot()
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerTranslations();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        if (!app()->routesAreCached()) {
            require __DIR__ . '/../Http/routes.php';
        }

        // Enqueue module CSS via the standard stylesheets filter
        \Eventy::addFilter('stylesheets', function ($styles) {
            $styles[] = \Module::getPublicPath(ORGPORTAL_MODULE) . '/css/module.css';
            return $styles;
        });

        $this->registerMenuHooks();
        $this->registerCustomerHooks();
        $this->registerConversationHooks();
        $this->registerKanbanHooks();
        $this->registerSearchHooks();
        if (\Module::isActive('enduserportal')) {
            $this->registerEupHooks();
        }
        $this->registerNotificationHooks();
        $this->registerPermissionHooks();
        if (\Module::isActive('apiwebhooks')) {
            $this->registerApiWebhooksHooks();
        }
    }

    public function register()
    {
        //
    }

    // -------------------------------------------------------------------------
    // Hooks
    // -------------------------------------------------------------------------

    protected function registerPermissionHooks()
    {
        // Register the "Allow managing organizations" checkbox in the user
        // permissions tab. FreeScout iterates this list both when rendering the
        // permissions form and when saving it, so no save hook is needed.
        \Eventy::addFilter('user_permissions.list', function ($permissions) {
            $permissions[] = self::PERM_MANAGE_ORGANIZATIONS;
            $permissions[] = self::PERM_MANAGE_TEMPLATES;
            return $permissions;
        });

        // Provide the localized label for our permission IDs.
        \Eventy::addFilter('user_permissions.name', function ($name, $permission) {
            if ($permission == self::PERM_MANAGE_ORGANIZATIONS) {
                return __('orgportal::messages.perm_manage_organizations');
            }
            if ($permission == self::PERM_MANAGE_TEMPLATES) {
                return __('orgportal::messages.perm_manage_templates');
            }
            return $name;
        }, 20, 2);
    }

    /**
     * Whether the given user may access the OrgPortal admin pages.
     * Admins always can; non-admins need the PERM_MANAGE_ORGANIZATIONS permission.
     */
    public static function userCanManageOrganizations($user)
    {
        if (!$user) {
            return false;
        }
        if ($user->isAdmin()) {
            return true;
        }
        return $user->hasPermission(self::PERM_MANAGE_ORGANIZATIONS);
    }

    public static function userCanManageTemplates($user)
    {
        if (!$user) {
            return false;
        }
        if ($user->isAdmin()) {
            return true;
        }
        return $user->hasPermission(self::PERM_MANAGE_TEMPLATES);
    }

    protected function registerMenuHooks()
    {
        // Make the "Manage" dropdown visible for non-admin users who only hold
        // the manage-organizations permission (otherwise FreeScout hides the
        // whole dropdown for them and the link below would never render).
        \Eventy::addFilter('menu.manage.can_view', function ($can_view) {
            if ($can_view) {
                return $can_view;
            }
            return self::userCanManageOrganizations(auth()->user());
        }, 20, 1);

        \Eventy::addAction('menu.manage.append', function () {
            if (!\Route::has('orgportal.admin.index')) {
                return;
            }
            if (!self::userCanManageOrganizations(auth()->user())) {
                return;
            }
            echo '<li><a href="' . route('orgportal.admin.index') . '">'
                . __('orgportal::messages.organizations') . '</a></li>';
        }, 20, 0);

        // Add OrgPortal item to the mailbox settings sidebar
        \Eventy::addAction('mailboxes.settings.menu', function ($mailbox) {
            if (auth()->user() && auth()->user()->isAdmin()) {
                echo \View::make('orgportal::partials.mailbox_settings_menu', ['mailbox' => $mailbox])->render();
            }
        }, 50);
    }

    protected function registerCustomerHooks()
    {
        // Inject organization selector into the customer edit form
        \Eventy::addAction('customer.edit.after_fields', function ($customer, $errors) {
            $organizations   = \Modules\OrgPortal\Models\Organization::orderBy('name')->get();
            $currentMember   = \Modules\OrgPortal\Models\OrganizationMember::where('customer_id', $customer->id)
                ->where('is_active', true)->first();
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

            // Operate on the customer's active membership (a customer may have
            // historical inactive memberships in other organizations).
            $existing = \Modules\OrgPortal\Models\OrganizationMember::where('customer_id', $customer->id)
                ->where('is_active', true)->first();

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
                // Moving to a different org invalidates the unit (units belong
                // to a single organization).
                if ((int) $existing->organization_id !== (int) $orgId) {
                    $existing->unit_id = null;
                }
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
        // Single ticket view — renders after the entire subject+number block
        \Eventy::addAction('conversation.after_subject_block', function ($conversation, $mailbox) {
            $enabled = $this->badgeEnabled('show_badge_conversation', $mailbox->id);
            if (!$enabled || !$conversation->customer_id) {
                return;
            }

            $member = OrganizationMember::where('customer_id', $conversation->customer_id)
                ->with('organization')
                ->first();

            if (!$member || !$member->organization) {
                return;
            }

            $searchBase = rtrim(url(\Helper::getSubdirectory() . 'search'), '/');
            $searchUrl  = $searchBase . '?' . http_build_query(['f' => ['organization' => $member->organization_id]]);

            echo view('orgportal::partials.org_badge', [
                'organization' => $member->organization,
                'searchUrl'    => $searchUrl,
            ])->render();
        }, 5, 2);

        // Conversations list — fires before the subject text, matching tag placement
        // (before_subject does NOT fire in Kanban cards, so no route check needed)
        \Eventy::addAction('conversations_table.before_subject', function ($conversation) {
            static $enabled = [];
            static $cache   = [];

            if (!$conversation || !$conversation->customer_id) {
                return;
            }
            $mailboxId = (int) $conversation->mailbox_id;
            if (!$mailboxId) {
                return;
            }
            if (!isset($enabled[$mailboxId])) {
                $enabled[$mailboxId] = $this->badgeEnabled('show_badge_conversation', $mailboxId);
            }
            if (!$enabled[$mailboxId]) {
                return;
            }

            $customerId = $conversation->customer_id;
            if (!array_key_exists($customerId, $cache)) {
                $cache[$customerId] = OrganizationMember::where('customer_id', $customerId)
                    ->with('organization')
                    ->first();
            }

            $member = $cache[$customerId];
            if (!$member || !$member->organization) {
                return;
            }

            // Conversations list: badge is injected inside the row's <a> link,
            // so render it as a non-clickable <span> to avoid nested <a> (which
            // breaks the row link and the layout).
            echo view('orgportal::partials.org_badge', [
                'organization' => $member->organization,
                'searchUrl'    => '',
                'asLink'       => false,
            ])->render();
        }, 20, 1);
    }

    protected function registerKanbanHooks()
    {
        // Show org badge on Kanban cards — only fires on Kanban route.
        // (conversations list now uses conversations_table.before_subject instead)
        \Eventy::addAction('conversations_table.after_subject', function ($conversation) {
            if (!\Route::is('kanban.*')) {
                return;
            }

            static $enabled = [];
            static $cache   = [];

            if (!$conversation || !$conversation->customer_id) {
                return;
            }
            $mailboxId = (int) $conversation->mailbox_id;
            if (!$mailboxId) {
                return;
            }
            if (!isset($enabled[$mailboxId])) {
                $enabled[$mailboxId] = $this->badgeEnabled('show_badge_kanban', $mailboxId);
            }
            if (!$enabled[$mailboxId]) {
                return;
            }

            $customerId = $conversation->customer_id;
            if (!array_key_exists($customerId, $cache)) {
                $cache[$customerId] = OrganizationMember::where('customer_id', $customerId)
                    ->with('organization')
                    ->first();
            }

            $member = $cache[$customerId];
            if (!$member || !$member->organization) {
                return;
            }

            $searchBase = rtrim(url(\Helper::getSubdirectory() . 'search'), '/');
            $searchUrl  = $searchBase . '?' . http_build_query(['f' => ['organization' => $member->organization_id]]);

            echo view('orgportal::partials.org_badge', [
                'organization' => $member->organization,
                'searchUrl'    => $searchUrl,
            ])->render();
        }, 20, 1);

        \Eventy::addAction('layout.body_bottom', function () {
            if (!\Route::is('kanban.show')) {
                return;
            }

            $organizations = Organization::orderBy('name')->get(['id', 'name']);
            if ($organizations->isEmpty()) {
                return;
            }

            echo view('orgportal::partials.kanban_org_filter', [
                'organizations' => $organizations,
            ])->render();
        }, 25, 0);
    }

    protected function registerApiWebhooksHooks()
    {
        // Add a link to the OrgPortal API docs on the ApiWebhooks settings page
        // (/app-settings/apiwebhooks), without modifying the ApiWebhooks module.
        //
        // ApiWebhooks registers `settings.view` at priority 20 returning
        // 'apiwebhooks::settings'. We register at a higher priority (30) so our
        // filter runs last and wins, swapping the view for a thin OrgPortal
        // wrapper that re-includes the original ApiWebhooks view and appends a
        // plain server-rendered link.
        //
        // Why not inject via JS on layout.body_bottom: FreeScout sets a
        // Content-Security-Policy (see layout.app cspMetaTag / cspNonceAttr).
        // body_bottom <script> output via echo carries no nonce, so the browser
        // refuses to execute it — the markup shows in the source but the link
        // never appears. Rendering real HTML server-side avoids CSP entirely.
        \Eventy::addFilter('settings.view', function ($view, $section) {
            if ($section === 'apiwebhooks') {
                return 'orgportal::partials.apiwebhooks_settings_wrapper';
            }
            return $view;
        }, 30, 2);
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
                ->whereIn('role', ['manager', 'unit_manager', 'global_manager'])
                ->where('is_active', true)
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

        // Notification bell — for ALL authenticated portal customers (managers + regular users).
        \Eventy::addAction('layout.body_bottom', function () {
            if (!\EndUserPortal::isEup()) {
                return;
            }

            $customer = \EndUserPortal::authCustomer();
            if (!$customer) {
                return;
            }

            $mailboxId = request()->route('mailbox_id');
            if (!$mailboxId) {
                return;
            }

            echo view('orgportal::partials.eup_notifications', [
                'mailbox_id' => $mailboxId,
            ])->render();
        }, 21, 0);
    }

    protected function registerNotificationHooks()
    {
        // New ticket created by a customer.
        \Eventy::addAction('conversation.created_by_customer', function ($conversation, $thread, $customer) {
            if (!$customer || !$customer->id) return;
            $this->fireOrgNotification(
                OrgNotificationSubscription::EVENT_NEW_TICKET,
                $conversation,
                $customer->id,
                $thread
            );
            $this->createPortalNotificationsForManagers(
                $conversation,
                $thread,
                $customer->id,
                OrgPortalNotification::TYPE_NEW_TICKET
            );
        }, 20, 3);

        // Agent reply on a ticket.
        \Eventy::addAction('conversation.user_replied', function ($conversation, $thread) {
            $customerId = optional($conversation->customer)->id;
            $this->fireOrgNotification(
                OrgNotificationSubscription::EVENT_REPLY_AGENT,
                $conversation,
                $customerId,
                $thread
            );
            // Notify the ticket author (regular portal user).
            if ($customerId) {
                OrgPortalNotification::createIfNotDuplicate(
                    $customerId,
                    $conversation->id,
                    $thread->id ?? null,
                    OrgPortalNotification::TYPE_NEW_REPLY
                );
            }
            // Notify org managers.
            $this->createPortalNotificationsForManagers(
                $conversation,
                $thread,
                $customerId,
                OrgPortalNotification::TYPE_NEW_REPLY
            );
        }, 20, 2);

        // Customer reply on an existing ticket.
        \Eventy::addAction('conversation.customer_replied', function ($conversation, $thread, $customer) {
            if (!$customer || !$customer->id) return;
            $this->fireOrgNotification(
                OrgNotificationSubscription::EVENT_REPLY_CUSTOMER,
                $conversation,
                $customer->id,
                $thread
            );
            $this->createPortalNotificationsForManagers(
                $conversation,
                $thread,
                $customer->id,
                OrgPortalNotification::TYPE_CUSTOMER_REPLY
            );
        }, 20, 3);
    }

    /**
     * Create portal (in-app) notifications for all active managers in the org,
     * excluding the author themselves.
     */
    protected function createPortalNotificationsForManagers(
        $conversation,
        $thread,
        ?int $authorCustomerId,
        string $type
    ): void {
        if (!$authorCustomerId) return;

        $authorMember = OrganizationMember::where('customer_id', $authorCustomerId)
            ->where('is_active', true)
            ->first();

        if (!$authorMember) return;

        $managers = OrganizationMember::where('organization_id', $authorMember->organization_id)
            ->where('role', 'manager')
            ->where('is_active', true)
            ->where('customer_id', '!=', $authorCustomerId)
            ->get();

        foreach ($managers as $manager) {
            OrgPortalNotification::createIfNotDuplicate(
                $manager->customer_id,
                $conversation->id,
                $thread->id ?? null,
                $type
            );
        }
    }

    /**
     * Find subscribed managers for an org event and send notification emails.
     */
    protected function fireOrgNotification(string $event, $conversation, ?int $authorCustomerId, $thread): void
    {
        if (!$authorCustomerId) return;

        $authorMember = OrganizationMember::where('customer_id', $authorCustomerId)
            ->where('is_active', true)
            ->first();

        if (!$authorMember) return;

        // All active managers in the same org (excluding the author themselves).
        $managers = OrganizationMember::where('organization_id', $authorMember->organization_id)
            ->where('role', 'manager')
            ->where('is_active', true)
            ->where('customer_id', '!=', $authorCustomerId)
            ->with('customer')
            ->get();

        if ($managers->isEmpty()) return;

        foreach ($managers as $manager) {
            if (!$manager->customer) continue;

            if (!OrgNotificationSubscription::memberIsSubscribed($manager->id, $event, $authorMember->unit_id)) {
                continue;
            }

            $email = $this->getCustomerEmail($manager->customer);
            if (!$email) continue;

            [$subject, $body] = $this->renderNotificationTemplate($event, $manager, $authorMember, $conversation, $thread);

            \Modules\OrgPortal\Jobs\SendOrgNotification::dispatch(
                (int) $conversation->mailbox_id,
                $email,
                $subject,
                $body
            )->onQueue('emails');
        }
    }

    /**
     * Render subject + HTML body from stored template, replacing macros.
     * Falls back to a simple built-in template when no custom template is saved.
     */
    protected function renderNotificationTemplate(string $event, $manager, $authorMember, $conversation, $thread): array
    {
        $subjectKey = 'orgportal.tpl_' . $event . '_subject';
        $bodyKey    = 'orgportal.tpl_' . $event . '_body';

        $subject = \Option::get($subjectKey, '');
        $body    = \Option::get($bodyKey, '');

        // Build ticket URL once.
        $ticketUrl = null;
        if (\Module::isActive('enduserportal')) {
            try {
                $encoded   = \EndUserPortal::encodeMailboxId($conversation->mailbox_id);
                $ticketUrl = route('orgportal.portal.ticket', [
                    'mailbox_id'      => $encoded,
                    'conversation_id' => $conversation->id,
                ]);
            } catch (\Exception $e) {}
        }

        $authorCustomer = $authorMember->customer;
        $org            = $authorMember->organization;
        $unit           = $authorMember->unit;

        $now = $thread ? \Carbon\Carbon::parse($thread->created_at) : \Carbon\Carbon::parse($conversation->created_at);

        $rawMacros = [
            '{manager_name}'    => $manager->customer ? $manager->customer->getFullName() : '',
            '{author_name}'     => $authorCustomer ? $authorCustomer->getFullName() : '',
            '{org_name}'        => $org ? $org->name : '',
            '{unit_name}'       => $unit ? $unit->name : '',
            '{subject}'         => $conversation->subject ?? '',
            '{ticket_number}'   => '#' . ($conversation->number ?? ''),
            '{ticket_url}'      => $ticketUrl ?? '',
            '{created_date}'    => \Carbon\Carbon::parse($conversation->created_at)->format('d.m.Y'),
            '{created_time}'    => \Carbon\Carbon::parse($conversation->created_at)->format('H:i'),
            '{created_datetime}'=> \Carbon\Carbon::parse($conversation->created_at)->format('d.m.Y H:i'),
            '{reply_date}'      => $now->format('d.m.Y'),
            '{reply_time}'      => $now->format('H:i'),
            '{reply_datetime}'  => $now->format('d.m.Y H:i'),
            '{reply_text}'      => $thread ? ($thread->body ?? '') : '',
            '{ticket_text}'     => $thread ? ($thread->body ?? '') : '',
        ];

        $htmlMacros = array_map(function ($v, $k) {
            // reply_text/ticket_text вже HTML — не екрануємо повторно
            return in_array($k, ['{reply_text}', '{ticket_text}', '{ticket_url}']) ? $v : e($v);
        }, $rawMacros, array_keys($rawMacros));
        $htmlMacros = array_combine(array_keys($rawMacros), $htmlMacros);

        if ($subject === '' || $body === '') {
            // Built-in fallback template.
            return $this->builtinNotificationTemplate($event, $htmlMacros, $ticketUrl);
        }

        $subject = str_replace(array_keys($rawMacros), array_values($rawMacros), $subject);
        $body    = str_replace(array_keys($htmlMacros), array_values($htmlMacros), $body);

        return [$subject, $body];
    }

    protected function builtinNotificationTemplate(string $event, array $macros, ?string $ticketUrl): array
    {
        if ($event === OrgNotificationSubscription::EVENT_NEW_TICKET) {
            $subject = __('orgportal::messages.new_ticket_from', ['name' => $macros['{author_name}']]);
            $body = '<p>' . __('orgportal::messages.email_hello') . ', ' . e($macros['{manager_name}']) . '</p>'
                  . '<p>' . __('orgportal::messages.email_new_ticket_intro') . '</p>'
                  . '<table style="width:100%;border-collapse:collapse;margin:16px 0">'
                  . '<tr><td style="color:#666;width:130px">' . __('orgportal::messages.email_from') . ':</td><td><strong>' . e($macros['{author_name}']) . '</strong></td></tr>'
                  . '<tr><td style="color:#666">' . __('orgportal::messages.email_subject') . ':</td><td><strong>' . e($macros['{subject}']) . '</strong></td></tr>'
                  . '<tr><td style="color:#666">' . __('orgportal::messages.email_ticket_number') . ':</td><td>' . e($macros['{ticket_number}']) . '</td></tr>'
                  . '</table>'
                  . ($macros['{reply_text}'] ? '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">' . $macros['{reply_text}'] . '</div>' : '');
        } else {
            $labelKey = $event === OrgNotificationSubscription::EVENT_REPLY_AGENT
                ? 'email_reply_agent_intro' : 'email_reply_customer_intro';
            $subject = __('orgportal::messages.email_reply_subject', [
                'number' => $macros['{ticket_number}'],
                'subject' => $macros['{subject}'],
            ]);
            $body = '<p>' . __('orgportal::messages.email_hello') . ', ' . e($macros['{manager_name}']) . '</p>'
                  . '<p>' . __('orgportal::messages.' . $labelKey) . '</p>'
                  . '<table style="width:100%;border-collapse:collapse;margin:16px 0">'
                  . '<tr><td style="color:#666;width:130px">' . __('orgportal::messages.email_from') . ':</td><td><strong>' . e($macros['{author_name}']) . '</strong></td></tr>'
                  . '<tr><td style="color:#666">' . __('orgportal::messages.email_subject') . ':</td><td><strong>' . e($macros['{subject}']) . '</strong></td></tr>'
                  . '<tr><td style="color:#666">' . __('orgportal::messages.email_ticket_number') . ':</td><td>' . e($macros['{ticket_number}']) . '</td></tr>'
                  . '</table>'
                  . ($macros['{reply_text}'] ? '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">' . $macros['{reply_text}'] . '</div>' : '');
        }

        if ($ticketUrl) {
            $body .= '<p><a href="' . e($ticketUrl) . '" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">'
                   . __('orgportal::messages.view_ticket') . '</a></p>';
        }

        $body .= '<p style="margin-top:32px;font-size:12px;color:#999">' . __('orgportal::messages.email_new_ticket_footer') . '</p>';

        return [$subject, $body];
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Check badge visibility for a given setting name and mailbox.
     * Per-mailbox key takes precedence; defaults to true when not set.
     */
    protected function badgeEnabled(string $setting, int $mailboxId): bool
    {
        $perMailbox = \Option::get('orgportal.' . $setting . '_' . $mailboxId);
        return $perMailbox !== null ? (bool) $perMailbox : true;
    }

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
