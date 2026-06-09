<?php

namespace Modules\OrgPortal\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Re-applies the End-User Portal locale chosen via the EUPSWLANG module.
 *
 * Why this exists
 * ---------------
 * EUPSWLANG registers its SetEupLocale middleware in the *global* stack
 * (via $kernel->pushMiddleware()), so it runs BEFORE route middleware and
 * correctly sets app()->setLocale() to the visitor's chosen language.
 *
 * However the OrgPortal portal route group includes \App\Http\Middleware\Localize,
 * which runs AFTER the global stack and resets the locale from the FreeScout
 * user session (session('user_locale')). For an admin browsing the portal in
 * the same session this overrides the chosen portal language back to the
 * system default (e.g. 'uk'), so portal pages always render in Ukrainian.
 *
 * This middleware is registered LAST in the portal route group, after Localize,
 * and re-applies the EUPSWLANG locale so the visitor's choice wins.
 *
 * It does NOT depend on EUPSWLANG being installed: it reads the raw
 * (unencrypted) cookie EUPSWLANG writes, falls back to the session key it uses,
 * and validates the locale by checking for the corresponding EndUserPortal
 * language JSON file. If EUPSWLANG is absent, no cookie/session value is set
 * and this middleware is a no-op.
 */
class ReapplyEupLocale
{
    /**
     * Cookie name EUPSWLANG persists the chosen locale in (raw, unencrypted).
     */
    const COOKIE_NAME = 'eup_locale';

    /**
     * Session key EUPSWLANG mirrors the chosen locale into.
     */
    const SESSION_KEY = 'enduserportal.locale';

    public function handle(Request $request, Closure $next)
    {
        // Read the raw cookie directly: EUPSWLANG writes it unencrypted on
        // purpose, so it lives in $_COOKIE rather than the decrypted bag.
        $locale = $_COOKIE[self::COOKIE_NAME] ?? null;

        if (!$locale) {
            $locale = session(self::SESSION_KEY);
        }

        if (is_string($locale) && $locale !== '' && $this->isValidLocale($locale)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }

    /**
     * A locale is valid when EndUserPortal ships a JSON file for it.
     * 'en' is always valid: it is the baseline and needs no JSON file.
     */
    private function isValidLocale(string $locale): bool
    {
        // Guard against path traversal / unexpected values.
        if (!preg_match('/^[A-Za-z_-]+$/', $locale)) {
            return false;
        }

        if ($locale === 'en') {
            return true;
        }

        return is_file(base_path('Modules/EndUserPortal/Resources/lang/' . $locale . '.json'));
    }
}
