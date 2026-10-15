<?php

namespace Modules\OrgPortal\Http\Middleware;

use Closure;
use Modules\OrgPortal\Providers\OrgPortalServiceProvider;

/**
 * API rights for a request made with a user's personal API key.
 *
 * Newer API & Webhooks versions accept personal keys: ApiAuth logs the key's
 * owner in and stores the key in the `user_api_key` request attribute. The
 * OrgPortal API was written for the single install-wide key, so on its own it
 * would let any agent with API access create organisations or bind customers
 * to them. A personal key gets exactly what its owner may do in the admin
 * pages: "manage organisations" for everything, admin for deleting an
 * organisation (admin-only in the admin pages too).
 *
 * The install-wide key and older API & Webhooks versions (no attribute) are
 * not affected.
 */
class OrgPortalApiUserAccess
{
    const ADMIN_ONLY_ROUTES = [
        'orgportal.api.organizations.delete',
    ];

    public function handle($request, Closure $next)
    {
        if (!$request->attributes->get('user_api_key')) {
            return $next($request);
        }

        $user = auth()->user();
        $route = $request->route() ? $request->route()->getName() : null;

        $allowed = in_array($route, self::ADMIN_ONLY_ROUTES, true)
            ? ($user && $user->isAdmin())
            : OrgPortalServiceProvider::userCanManageOrganizations($user);

        if (!$allowed) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
