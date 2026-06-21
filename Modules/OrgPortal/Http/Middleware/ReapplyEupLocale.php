<?php

namespace Modules\OrgPortal\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Re-applies the portal locale after Localize middleware resets it.
 *
 * Localize (route middleware) overrides app locale from the admin session.
 * This middleware runs AFTER Localize and restores the portal locale from
 * the unencrypted cookie written by OrgPortalSetLocale (global middleware)
 * or by EupSwLang (global middleware, same cookie name).
 */
class ReapplyEupLocale
{
    const COOKIE_NAME = 'eup_locale';
    const SESSION_KEY = 'enduserportal.locale';

    public function handle(Request $request, Closure $next)
    {
        // Both EupSwLang and OrgPortalSetLocale write this cookie from the
        // global stack, bypassing EncryptCookies (route middleware group).
        // So the raw cookie value is always plain text.
        $locale = $_COOKIE[self::COOKIE_NAME] ?? session(self::SESSION_KEY);

        if (is_string($locale) && $locale !== '' && $this->isValidLocale($locale)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }

    private function isValidLocale(string $locale): bool
    {
        if (!preg_match('/^[A-Za-z_-]+$/', $locale)) return false;
        if ($locale === 'en') return true;
        return is_file(base_path('Modules/EndUserPortal/Resources/lang/' . $locale . '.json'));
    }
}
