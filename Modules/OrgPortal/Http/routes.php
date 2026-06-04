<?php

use Illuminate\Support\Facades\Route;

$subdirectory = \Helper::getSubdirectory();

// ─── Admin routes (require FreeScout auth + admin role) ───────────────────────
Route::group([
    'prefix'     => $subdirectory . 'orgportal/admin',
    'middleware' => ['web', 'auth', 'admin'],
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

    Route::get('customers/search', 'OrgPortalAdminController@searchCustomers')
        ->name('orgportal.admin.customers.search');

    Route::get('settings', 'OrgPortalAdminController@settings')
        ->name('orgportal.admin.settings');

    Route::post('settings', 'OrgPortalAdminController@saveSettings')
        ->name('orgportal.admin.settings.save');
});

// ─── API routes — requires API and Webhooks module (middleware: api.key) ──────
if (array_key_exists('api.key', app('router')->getMiddleware())) {
    Route::group([
        'prefix'     => $subdirectory . 'api',
        'middleware' => ['api.key'],
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
    ],
    'namespace'  => 'Modules\OrgPortal\Http\Controllers',
], function () {

    Route::get('company-tickets', 'OrgPortalFrontController@companyTickets')
        ->name('orgportal.portal.company-tickets');

    Route::get('ticket/{conversation_id}', 'OrgPortalFrontController@viewTicket')
        ->name('orgportal.portal.ticket');

    Route::post('ticket/{conversation_id}/reply', 'OrgPortalFrontController@replyTicket')
        ->name('orgportal.portal.ticket.reply');

    Route::get('settings', 'OrgPortalFrontController@settings')
        ->name('orgportal.portal.settings');

    Route::post('settings', 'OrgPortalFrontController@saveSettings')
        ->name('orgportal.portal.settings.save');
});
endif;
