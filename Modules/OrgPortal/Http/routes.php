<?php

use Illuminate\Support\Facades\Route;

$subdirectory = \Helper::getSubdirectory();

// ─── Admin routes ─────────────────────────────────────────────────────────────
// Require FreeScout auth. Per-action authorization (admin OR the
// "manage organizations" user permission) is enforced inside the controller;
// destructive/settings actions remain admin-only there.
Route::group([
    'prefix'     => $subdirectory . 'orgportal/admin',
    'middleware' => ['web', 'auth'],
    'namespace'  => 'Modules\OrgPortal\Http\Controllers',
], function () {

    Route::get('organizations', 'OrgPortalAdminController@index')
        ->name('orgportal.admin.index');

    Route::get('organizations/create', 'OrgPortalAdminController@create')
        ->name('orgportal.admin.create');

    Route::post('organizations', 'OrgPortalAdminController@store')
        ->name('orgportal.admin.store');

    Route::get('organizations/{id}/edit', 'OrgPortalAdminController@edit')
        ->name('orgportal.admin.edit');

    Route::put('organizations/{id}', 'OrgPortalAdminController@update')
        ->name('orgportal.admin.update');

    Route::delete('organizations/{id}', 'OrgPortalAdminController@destroy')
        ->name('orgportal.admin.destroy');

    Route::post('organizations/{id}/members', 'OrgPortalAdminController@addMember')
        ->name('orgportal.admin.members.add');

    Route::delete('organizations/{id}/members/{memberId}', 'OrgPortalAdminController@removeMember')
        ->name('orgportal.admin.members.remove');

    Route::post('organizations/{id}/members/{memberId}/role', 'OrgPortalAdminController@updateMemberRole')
        ->name('orgportal.admin.members.role');

    Route::post('organizations/{id}/members/{memberId}/toggle-active', 'OrgPortalAdminController@toggleMemberActive')
        ->name('orgportal.admin.members.toggle');

    Route::post('organizations/{id}/units', 'OrgPortalAdminController@addUnit')
        ->name('orgportal.admin.units.add');

    Route::put('organizations/{id}/units/{unitId}', 'OrgPortalAdminController@renameUnit')
        ->name('orgportal.admin.units.rename');

    Route::delete('organizations/{id}/units/{unitId}', 'OrgPortalAdminController@deleteUnit')
        ->name('orgportal.admin.units.delete');

    Route::get('customers/search', 'OrgPortalAdminController@searchCustomers')
        ->name('orgportal.admin.customers.search');

    Route::get('impersonate/{customer_id}/{mailbox_id}', 'OrgPortalAdminController@impersonatePortalLink')
        ->name('orgportal.admin.impersonate');

    Route::get('api-docs', 'OrgPortalAdminController@apiDocs')
        ->name('orgportal.admin.api-docs');
});

// ─── Per-mailbox admin routes ─────────────────────────────────────────────────
Route::group([
    'prefix'     => $subdirectory . 'mailbox/settings',
    'middleware' => ['web', 'auth'],
    'namespace'  => 'Modules\OrgPortal\Http\Controllers',
], function () {
    Route::get('{id}/orgportal', 'OrgPortalAdminController@mailboxSettings')
        ->name('orgportal.admin.mailbox-settings');
    Route::post('{id}/orgportal', 'OrgPortalAdminController@saveMailboxSettings')
        ->name('orgportal.admin.mailbox-settings.save');
});

// ─── API routes — requires API and Webhooks module ────────────────────────────
if (\Module::isActive('apiwebhooks')) {
    Route::group([
        'prefix'     => \Helper::getSubdirectory(true) . 'api',
        'middleware' => ['bindings', \Modules\ApiWebhooks\Http\Middleware\ApiAuth::class],
        'namespace'  => 'Modules\OrgPortal\Http\Controllers\Api',
    ], function () {

        Route::get('organizations', 'OrgPortalApiController@listOrganizations')
            ->name('orgportal.api.organizations.list');

        Route::post('organizations', 'OrgPortalApiController@createOrganization')
            ->name('orgportal.api.organizations.create');

        Route::get('organizations/{id}', 'OrgPortalApiController@getOrganization')
            ->name('orgportal.api.organizations.get');

        Route::put('organizations/{id}', 'OrgPortalApiController@updateOrganization')
            ->name('orgportal.api.organizations.update');

        Route::delete('organizations/{id}', 'OrgPortalApiController@deleteOrganization')
            ->name('orgportal.api.organizations.delete');

        Route::get('organizations/{id}/units', 'OrgPortalApiController@listUnits')
            ->name('orgportal.api.units.list');

        Route::post('organizations/{id}/units', 'OrgPortalApiController@createUnit')
            ->name('orgportal.api.units.create');

        Route::put('units/{unitId}', 'OrgPortalApiController@updateUnit')
            ->name('orgportal.api.units.update');

        Route::delete('units/{unitId}', 'OrgPortalApiController@deleteUnit')
            ->name('orgportal.api.units.delete');

        Route::get('customers/{customerId}/organization', 'OrgPortalApiController@getCustomerOrganization')
            ->name('orgportal.api.customer.org.get');

        Route::put('customers/{customerId}/organization', 'OrgPortalApiController@setCustomerOrganization')
            ->name('orgportal.api.customer.org.set');

        Route::delete('customers/{customerId}/organization', 'OrgPortalApiController@removeCustomerOrganization')
            ->name('orgportal.api.customer.org.remove');
    });
}

// ─── Portal (EUP) routes — only when End-User Portal module is active ────────
if (\Module::isActive('enduserportal')) :
Route::group([
    'prefix'    => $subdirectory . 'help/{mailbox_id}/org',
    'middleware' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \App\Http\Middleware\HttpsRedirect::class,
        \App\Http\Middleware\Localize::class,
        \App\Http\Middleware\FrameGuard::class,
        // Must run AFTER Localize: re-applies the EUPSWLANG portal locale that
        // Localize otherwise resets from the FreeScout user session. No-op when
        // EUPSWLANG is not installed.
        \Modules\OrgPortal\Http\Middleware\ReapplyEupLocale::class,
    ],
    'namespace'  => 'Modules\OrgPortal\Http\Controllers',
], function () {

    Route::get('company-tickets', 'OrgPortalFrontController@companyTickets')
        ->name('orgportal.portal.company-tickets');

    Route::get('ticket/{conversation_id}', 'OrgPortalFrontController@viewTicket')
        ->name('orgportal.portal.ticket');

    Route::post('ticket/{conversation_id}/reply', 'OrgPortalFrontController@replyTicket')
        ->name('orgportal.portal.ticket.reply');

    Route::post('ticket/{conversation_id}/change-author', 'OrgPortalFrontController@changeAuthor')
        ->name('orgportal.portal.ticket.change-author');

    Route::post('ticket/{conversation_id}/close', 'OrgPortalFrontController@closeTicket')
        ->name('orgportal.portal.ticket.close');

    Route::get('settings', 'OrgPortalFrontController@settings')
        ->name('orgportal.portal.settings');

    Route::post('settings', 'OrgPortalFrontController@saveSettings')
        ->name('orgportal.portal.settings.save');

    // Structure management (global manager only — enforced in controller)
    Route::post('units', 'OrgPortalFrontController@createUnit')
        ->name('orgportal.portal.units.create');

    Route::put('units/{unit_id}', 'OrgPortalFrontController@renameUnit')
        ->name('orgportal.portal.units.rename');

    Route::delete('units/{unit_id}', 'OrgPortalFrontController@deleteUnit')
        ->name('orgportal.portal.units.delete');

    Route::post('members/{member_id}', 'OrgPortalFrontController@updateMember')
        ->name('orgportal.portal.members.update');

    Route::post('members/{member_id}/toggle-active', 'OrgPortalFrontController@toggleMemberActive')
        ->name('orgportal.portal.members.toggle');
});
endif;
