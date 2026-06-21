<?php

namespace Modules\OrgPortal\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Global middleware (registered via $kernel->pushMiddleware) that handles
 * ?eup_locale=xx when EupSwLang is NOT active.
 *
 * Because this runs in the global stack — before route middleware —
 * the redirect response bypasses EncryptCookies (route middleware group),
 * so the cookie is stored unencrypted. This mirrors EupSwLang's approach.
 */
class OrgPortalSetLocale
{
    const COOKIE_NAME     = 'eup_locale';
    const SESSION_KEY     = 'enduserportal.locale';
    const COOKIE_PATH     = '/help';
    const COOKIE_LIFETIME = 525600;

    public function handle(Request $request, Closure $next)
    {
        // Only act on portal requests.
        if (!$this->isPortalRequest($request)) {
            return $next($request);
        }

        // Yield to EupSwLang when active — it handles everything.
        if ($this->eupSwLangActive()) {
            return $next($request);
        }

        // Handle explicit locale switch via query parameter.
        if ($request->has('eup_locale')) {
            $locale = $request->get('eup_locale');

            if ($this->isValidLocale($locale)) {
                session([self::SESSION_KEY => $locale]);
                app()->setLocale($locale);
                $this->syncLocaleToMember($locale);

                $query    = $request->except('eup_locale');
                $cleanUrl = $request->url() . ($query ? '?' . http_build_query($query) : '');

                // Redirect before route middleware (EncryptCookies) sees the response
                // so the cookie is stored as plain text and readable via $_COOKIE.
                return redirect($cleanUrl)
                    ->withCookie(cookie(self::COOKIE_NAME, '', -1, '/'))
                    ->withCookie(cookie(self::COOKIE_NAME, $locale, self::COOKIE_LIFETIME, self::COOKIE_PATH, null, config('session.secure', false), true));
            }

            $query = $request->except('eup_locale');
            return redirect($request->url() . ($query ? '?' . http_build_query($query) : ''));
        }

        // Apply stored locale on every portal request.
        $locale = $_COOKIE[self::COOKIE_NAME] ?? session(self::SESSION_KEY);

        if ($locale && $this->isValidLocale($locale)) {
            session([self::SESSION_KEY => $locale]);
            app()->setLocale($locale);
            $this->syncLocaleToMember($locale);
        }

        return $next($request);
    }

    private function syncLocaleToMember(string $locale): void
    {
        try {
            $customer = \Modules\EndUserPortal\Facades\EndUserPortal::getAuthCustomer();
            if (!$customer) return;

            $member = \Modules\OrgPortal\Models\OrganizationMember::where('customer_id', $customer->id)
                ->where('is_active', true)
                ->first();

            if ($member && $member->locale !== $locale) {
                $member->locale = $locale;
                $member->save();
            }
        } catch (\Throwable $e) {
            // Non-critical.
        }
    }

    private function isValidLocale(string $locale): bool
    {
        if (!preg_match('/^[A-Za-z_-]+$/', $locale)) return false;
        if ($locale === 'en') return true;
        return is_file(base_path('Modules/EndUserPortal/Resources/lang/' . $locale . '.json'));
    }

    private function isPortalRequest(Request $request): bool
    {
        try {
            return (bool) \EndUserPortal::isEup();
        } catch (\Throwable $e) {
            return (bool) preg_match('#/help/#', $request->getRequestUri());
        }
    }

    private function eupSwLangActive(): bool
    {
        try {
            return \Module::isActive('eupswlang');
        } catch (\Throwable $e) {
            return false;
        }
    }
}
