<?php

namespace Modules\OrgPortal\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\OrgPortal\Models\Organization;
use Modules\OrgPortal\Models\OrganizationMember;
use Modules\OrgPortal\Models\OrgNotificationSubscription;
use Modules\OrgPortal\Models\OrgPortalNotification;
use Modules\OrgPortal\Models\OrgPortalThreadView;
use Modules\OrgPortal\Services\OrgAttribution;

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

        // Register Georgian locale in FreeScout's core Helper so EUP shows
        // the native name 'ქართული' instead of an empty string or 'KA'.
        if (!isset(\App\Misc\Helper::$locales['ka'])) {
            \App\Misc\Helper::$locales['ka'] = ['name' => 'ქართული', 'name_en' => 'Georgian'];
        }

        $this->deployGeorgianAssets();

        if (!app()->routesAreCached()) {
            require __DIR__ . '/../Http/routes.php';
        }

        // Enqueue module CSS via the standard stylesheets filter
        \Eventy::addFilter('stylesheets', function ($styles) {
            $styles[] = \Module::getPublicPath(ORGPORTAL_MODULE) . '/css/module.css';
            return $styles;
        });

        // Register global locale middleware (handles ?eup_locale=xx before route middleware).
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)
            ->pushMiddleware(\Modules\OrgPortal\Http\Middleware\OrgPortalSetLocale::class);

        $this->registerMenuHooks();
        $this->registerCustomerHooks();
        $this->registerConversationHooks();
        $this->registerKanbanHooks();
        $this->registerSearchHooks();
        if (\Module::isActive('enduserportal')) {
            $this->registerEupHooks();
        }
        $this->registerNotificationHooks();
        $this->registerAttributionHooks();
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
    /**
     * Returns all locales available in the EndUserPortal (same logic as EupSwLang).
     * 'en' is always included as the baseline.
     */
    public static function getAvailablePortalLocales(): array
    {
        static $cache = null;
        if ($cache !== null) return $cache;

        $cache = ['en' => 'English'];

        $langPath = base_path('Modules/EndUserPortal/Resources/lang');
        if (!is_dir($langPath)) {
            $langPath = base_path('modules/enduserportal/Resources/lang');
        }

        if (is_dir($langPath)) {
            foreach (glob($langPath . '/*.json') as $file) {
                $code = basename($file, '.json');
                $cache[$code] = self::getLocaleName($code);
            }
        }

        return $cache;
    }

    public static function getLocaleName(string $code): string
    {
        if (method_exists(\Helper::class, 'getLocaleData')) {
            try {
                $data = \Helper::getLocaleData($code);
                if (!empty($data['name'])) return $data['name'];
            } catch (\Throwable $e) {}
        }
        $names = [
            'en' => 'English', 'cs' => 'Čeština', 'da' => 'Dansk',
            'de' => 'Deutsch', 'es' => 'Español', 'fi' => 'Suomi',
            'fr' => 'Français', 'it' => 'Italiano', 'nl' => 'Nederlands',
            'no' => 'Norsk', 'pl' => 'Polski', 'pt-BR' => 'Português (BR)',
            'pt-PT' => 'Português (PT)', 'ro' => 'Română', 'ru' => 'Русский',
            'sk' => 'Slovenčina', 'sv' => 'Svenska', 'uk' => 'Українська',
            'zh-CN' => '中文(简体)', 'ka' => 'ქართული',
        ];
        return $names[$code] ?? strtoupper($code);
    }

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

        // Inject org search JS after jQuery (javascript action fires inside main <script> block at page bottom).
        // No route guard here: the customer_org_field partial (and its #orgportal_org_search
        // input) is rendered anywhere customer.edit.after_fields fires — including the customer
        // info panel inside a conversation/Kanban modal, not just the customers.update page.
        // The IIFE below already no-ops via `if (!elSearch) return;` when the input isn't present.
        \Eventy::addAction('javascript', function () {
            $searchUrl = route('orgportal.admin.organizations.search');
            echo "
(function () {
    var elSearch  = document.getElementById('orgportal_org_search');
    if (!elSearch) return;
    var searchUrl = elSearch.getAttribute('data-search-url');
    var elHidden  = document.getElementById('orgportal_org_id');
    var elClear   = document.getElementById('orgportal_org_clear');
    var elList    = document.getElementById('orgportal_org_suggestions');
    var elRole    = document.querySelector('.orgportal-role-row');
    var timer     = null;
    var activeXhr = null;

    function setVisible(el, show) { if (el) el.style.display = show ? '' : 'none'; }

    function selectOrg(id, name) {
        elHidden.value = id; elSearch.value = name;
        setVisible(elList, false); setVisible(elRole, true); setVisible(elClear, true);
    }
    function clearOrg() {
        elHidden.value = ''; elSearch.value = '';
        setVisible(elList, false); setVisible(elRole, false); setVisible(elClear, false);
    }
    function showSuggestions(data) {
        elList.innerHTML = '';
        if (!data || !data.length) { setVisible(elList, false); return; }
        data.forEach(function (o) {
            var li = document.createElement('li');
            li.style.cssText = 'padding:7px 12px;cursor:pointer;border-bottom:1px solid #eee;';
            li.textContent = o.name;
            li.addEventListener('mousedown', function (e) { e.preventDefault(); selectOrg(o.id, o.name); });
            li.addEventListener('mouseover', function () { li.style.background = '#f5f5f5'; });
            li.addEventListener('mouseout',  function () { li.style.background = ''; });
            elList.appendChild(li);
        });
        setVisible(elList, true);
    }
    elSearch.addEventListener('input', function () {
        var q = this.value.trim();
        clearTimeout(timer);
        if (activeXhr) { activeXhr.abort(); activeXhr = null; }
        if (!q) { clearOrg(); return; }
        timer = setTimeout(function () {
            var xhr = new XMLHttpRequest();
            activeXhr = xhr;
            var sep = searchUrl.indexOf('?') === -1 ? '?' : '&';
            xhr.open('GET', searchUrl + sep + 'q=' + encodeURIComponent(q), true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function () {
                if (xhr !== activeXhr) return;
                activeXhr = null;
                if (xhr.status === 200) {
                    try { showSuggestions(JSON.parse(xhr.responseText)); } catch(e) {}
                }
            };
            xhr.send();
        });
    });
    elSearch.addEventListener('blur', function () {
        setTimeout(function () {
            setVisible(elList, false);
            if (!elHidden.value) elSearch.value = '';
        }, 200);
    });
    if (elClear) elClear.addEventListener('click', clearOrg);
})();
";
        });

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

        // Show org / unit / role info in the customer sidebar (conversation view)
        \Eventy::addAction('customer.profile_data', function ($customer, $conversation) {
            $mailboxId = $conversation ? (int) $conversation->mailbox_id : 0;
            if ($mailboxId && !$this->badgeEnabled('show_org_in_profile', $mailboxId)) {
                return;
            }

            $member = OrganizationMember::where('customer_id', $customer->id)
                ->where('is_active', true)
                ->with('organization', 'unit')
                ->first();

            if (!$member || !$member->organization) {
                return;
            }

            $roleLabels = [
                'member'         => __('orgportal::messages.role_member'),
                'manager'        => __('orgportal::messages.role_manager_scoped'),
                'unit_manager'   => __('orgportal::messages.role_unit_manager'),
                'global_manager' => __('orgportal::messages.role_global_manager'),
            ];
            $roleLabel = $roleLabels[$member->role] ?? $member->role;

            echo view('orgportal::admin.partials.customer_profile_org', [
                'member'    => $member,
                'roleLabel' => $roleLabel,
            ])->render();
        }, 20, 2);
    }

    protected function registerConversationHooks()
    {
        // Single ticket view — renders after the entire subject+number block
        \Eventy::addAction('conversation.after_subject_block', function ($conversation, $mailbox) {
            $enabled = $this->badgeEnabled('show_badge_conversation', $mailbox->id);
            if (!$enabled || !$conversation->customer_id) {
                return;
            }

            $organization = null;

            if (OrgAttribution::snapshotEnabled() && $conversation->org_id) {
                $organization = Organization::find($conversation->org_id);
            }

            if (!$organization) {
                $organization = Organization::forCustomer($conversation->customer_id);
            }

            if (!$organization) {
                return;
            }

            $searchBase = rtrim(url(\Helper::getSubdirectory() . 'search'), '/');
            $searchUrl  = $searchBase . '?' . http_build_query(['f' => ['organization' => $organization->id]]);

            echo view('orgportal::partials.org_badge', [
                'organization' => $organization,
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
            $orgId      = $conversation->org_id ?? null;
            $snapshotOn = OrgAttribution::snapshotEnabled();
            $cacheKey   = $snapshotOn && $orgId ? 'org_' . $orgId : 'cust_' . $customerId;

            if (!array_key_exists($cacheKey, $cache)) {
                $snapshotOrg = ($snapshotOn && $orgId) ? Organization::find($orgId) : null;
                $memberOrg   = $snapshotOrg ? null : Organization::forCustomer($customerId);
                $cache[$cacheKey] = $snapshotOrg ?? $memberOrg;
            }

            $organization = $cache[$cacheKey];
            if (!$organization) {
                return;
            }

            // Conversations list: badge is injected inside the row's <a> link,
            // so render it as a non-clickable <span> to avoid nested <a> (which
            // breaks the row link and the layout).
            echo view('orgportal::partials.org_badge', [
                'organization' => $organization,
                'searchUrl'    => '',
                'asLink'       => false,
            ])->render();
        }, 20, 1);

        // Show "Manager viewed" info under each thread (like FreeScout's "Customer viewed")
        \Eventy::addAction('thread.meta', function ($thread, $loop, $threads, $conversation, $mailbox) {
            if ($thread->type !== \App\Thread::TYPE_MESSAGE) {
                return;
            }

            try {
                $views = \Modules\OrgPortal\Models\OrgPortalThreadView::forThread($thread->id);
                if ($views->isEmpty()) {
                    return;
                }

                foreach ($views as $view) {
                    if (!$view->customer) {
                        continue;
                    }
                    $name = $view->customer->getFullName();
                    $when = \App\User::dateDiffForHumansWithHours($view->viewed_at);
                    $role = $view->member ? $view->member->role : null;
                    $roleLabel = match ($role) {
                        'global_manager' => __('orgportal::messages.manager_org_label'),
                        'unit_manager'   => __('orgportal::messages.role_unit_manager'),
                        default          => __('orgportal::messages.manager'),
                    };
                    echo '<div class="thread-meta"><i class="glyphicon glyphicon-eye-open"></i> '
                        . e($roleLabel) . ' ' . e($name) . ' '
                        . __('orgportal::messages.manager_viewed_when', ['when' => $when])
                        . '</div>';
                }
            } catch (\Throwable $e) {
                \Log::error('[OrgPortal] thread.meta hook failed', ['error' => $e->getMessage(), 'thread' => $thread->id]);
            }
        }, 20, 5);
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

            $customerId = $conversation->customer_id;
            if (!array_key_exists($customerId, $cache)) {
                $cache[$customerId] = Organization::forCustomer($customerId);
            }

            $organization = $cache[$customerId];
            if (!$organization) {
                return;
            }

            if (!isset($enabled[$mailboxId])) {
                $enabled[$mailboxId] = $this->badgeEnabled('show_badge_kanban', $mailboxId);
            }

            if ($enabled[$mailboxId]) {
                $searchBase = rtrim(url(\Helper::getSubdirectory() . 'search'), '/');
                $searchUrl  = $searchBase . '?' . http_build_query(['f' => ['organization' => $organization->id]]);

                echo view('orgportal::partials.org_badge', [
                    'organization' => $organization,
                    'searchUrl'    => $searchUrl,
                ])->render();
            } else {
                // Render hidden marker so the JS org filter can still work
                // even when the visible badge is disabled for this mailbox.
                echo '<span class="orgportal-org-badge" data-org-id="' . (int) $organization->id . '" style="display:none"></span>';
            }
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
        // Re-apply the chosen portal locale right before every EUP view renders.
        // Something in FreeScout's pipeline resets the locale after middleware runs;
        // a View composer is the only reliable place to override it last.
        \View::composer('enduserportal::*', function ($view) {
            if (!preg_match('#/help/#', request()->getRequestUri())) return;

            // Both EupSwLang and OrgPortalSetLocale write eup_locale as plain text
            // from the global middleware stack (before EncryptCookies runs).
            $locale = $_COOKIE['eup_locale'] ?? session('enduserportal.locale');

            if ($locale && preg_match('/^[A-Za-z_-]+$/', $locale)) {
                app()->setLocale($locale);
            }
        });

        // Inject module CSS on every EUP page (the admin `stylesheets` filter is not
        // applied in the EUP portal layout, so we must inject it manually here).
        \Eventy::addAction('layout.body_bottom', function () {
            if (!\EndUserPortal::isEup()) return;
            $cssUrl = asset('modules/orgportal/css/module.css') . '?v=3';
            echo '<link rel="stylesheet" href="' . e($cssUrl) . '">';
        }, 5, 0);

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

        // Language switcher — only when OrgPortal's own switcher is enabled AND EupSwLang is not active.
        \Eventy::addAction('layout.body_bottom', function () {
            if (!\EndUserPortal::isEup()) return;
            if (\Module::isActive('eupswlang')) return;
            if (!\Option::get('orgportal.lang_switcher_enabled', false)) return;

            $mailboxId = request()->route('mailbox_id');
            if (!$mailboxId) return;

            $raw            = \Option::get('orgportal.lang_switcher_locales', []);
            $allowedLocales = is_array($raw) ? $raw : (json_decode($raw, true) ?: []);
            $allLocales     = self::getAvailablePortalLocales();
            $locales        = $allowedLocales
                ? array_intersect_key($allLocales, array_flip($allowedLocales))
                : $allLocales;

            if (empty($locales)) return;

            $currentLocale  = app()->getLocale();
            $baseUrl        = request()->url();
            $existingParams = request()->except('eup_locale');

            echo view('orgportal::partials.eup_lang_switcher', compact(
                'locales', 'currentLocale', 'baseUrl', 'existingParams'
            ))->render();
        }, 19, 0);

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

        // Clean up orphan records when a conversation is soft-deleted (moved to trash).
        \Eventy::addAction('conversation.deleted', function ($conversation) {
            OrgPortalNotification::where('conversation_id', $conversation->id)->delete();
            OrgPortalThreadView::where('conversation_id', $conversation->id)->delete();
        }, 20, 2);

        // Clean up orphan records when conversations are permanently deleted.
        \Eventy::addAction('conversations.before_delete_forever', function ($conversationIds) {
            if (empty($conversationIds)) return;
            OrgPortalNotification::whereIn('conversation_id', $conversationIds)->delete();
            OrgPortalThreadView::whereIn('conversation_id', $conversationIds)->delete();
        }, 20, 1);
    }

    protected function registerAttributionHooks(): void
    {
        // Stamp org snapshot on new conversations created via EUP or by a customer.
        \Eventy::addAction('conversation.created_by_customer', function ($conversation, $thread, $customer) {
            if ($conversation && $conversation->id) {
                OrgAttribution::attribute($conversation);
            }
        }, 30, 3);

        // Re-attribute conversation when a tag is attached (tag/tag_only modes).
        \Eventy::addAction('tag.attached', function ($tag, $conversationId) {
            OrgAttribution::attributeByTag((int) $conversationId, (int) $tag->id);
        }, 30, 2);

        // Register the backfill command and schedule it to run every 5 minutes.
        $this->commands([\Modules\OrgPortal\Console\BackfillOrgAttribution::class]);

        \Eventy::addFilter('schedule', function ($schedule) {
            if (\Option::get('orgportal.attribution_cron_enabled', false)) {
                $schedule->command('orgportal:backfill-attribution --limit=1000')
                         ->everyFiveMinutes()
                         ->withoutOverlapping();
            }
            return $schedule;
        });
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

            $prevLocale = app()->getLocale();
            try {
                if ($manager->locale) {
                    app()->setLocale($manager->locale);
                }
                [$subject, $body] = $this->renderNotificationTemplate($event, $manager, $authorMember, $conversation, $thread);
            } finally {
                app()->setLocale($prevLocale);
            }

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
        $locale  = app()->getLocale();
        $subject = \Option::get('orgportal.tpl_' . $locale . '_' . $event . '_subject', '');
        $body    = \Option::get('orgportal.tpl_' . $locale . '_' . $event . '_body', '');

        // Fallback chain: DB locale → default file locale → DB en → default file en
        if ($subject === '' || $body === '') {
            $defaults = \Modules\OrgPortal\Http\Controllers\OrgPortalAdminController::defaultTemplates($locale);
            $subject  = $defaults[$event]['subject'] ?? '';
            $body     = $defaults[$event]['body'] ?? '';
        }
        if ($subject === '' || $body === '') {
            $subject = \Option::get('orgportal.tpl_en_' . $event . '_subject', '');
            $body    = \Option::get('orgportal.tpl_en_' . $event . '_body', '');
        }
        if ($subject === '' || $body === '') {
            $defaults = \Modules\OrgPortal\Http\Controllers\OrgPortalAdminController::defaultTemplates('en');
            $subject  = $defaults[$event]['subject'] ?? '';
            $body     = $defaults[$event]['body'] ?? '';
        }

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
            // Built-in fallback template — pass rawMacros so subject lines are plain text,
            // not double-escaped HTML entities. The method applies e() itself for HTML body.
            return $this->builtinNotificationTemplate($event, $rawMacros, $ticketUrl);
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

    protected function deployGeorgianAssets(): void
    {
        $assets = __DIR__ . '/../Resources/assets/ka/';

        $targets = [
            $assets . 'parsley.js' => public_path('js/parsley/i18n/ka.js'),
        ];

        if (\Module::isActive('enduserportal')) {
            $targets[$assets . 'eup.json'] = base_path('Modules/EndUserPortal/Resources/lang/ka.json');
        }

        foreach ($targets as $src => $dst) {
            if (!file_exists($dst) && !copy($src, $dst)) {
                \Log::warning('[OrgPortal] Failed to deploy Georgian asset', [
                    'src' => $src, 'dst' => $dst,
                ]);
            }
        }
    }

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
