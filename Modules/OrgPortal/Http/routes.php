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

    // Member management
    Route::post('organizations/{id}/members', 'OrgPortalAdminController@addMember')
        ->name('orgportal.admin.members.add');

    Route::delete('organizations/{id}/members/{memberId}', 'OrgPortalAdminController@removeMember')
        ->name('orgportal.admin.members.remove');

    // AJAX customer search
    Route::get('customers/search', 'OrgPortalAdminController@searchCustomers')
        ->name('orgportal.admin.customers.search');
});

// ─── API routes — requires API and Webhooks module (middleware: api.key) ──────
// Middleware 'api.key' is registered by the API and Webhooks module.
// These routes are silently skipped if that middleware is not registered.
if (array_key_exists('api.key', app('router')->getMiddleware())) {
    Route::group([
        'prefix'     => $subdirectory . 'api',
        'middleware' => ['api.key'],
        'namespace'  => 'Modules\OrgPortal\Http\Controllers\Api',
    ], function () {

        // Organizations CRUD
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

        // Customer membership
        Route::get('customers/{customerId}/organization', 'OrgPortalApiController@getCustomerOrganization')
            ->name('orgportal.api.customer.org.get');

        Route::put('customers/{customerId}/organization', 'OrgPortalApiController@setCustomerOrganization')
            ->name('orgportal.api.customer.org.set');

        Route::delete('customers/{customerId}/organization', 'OrgPortalApiController@removeCustomerOrganization')
            ->name('orgportal.api.customer.org.remove');
    });
}

// ─── Portal (EUP) routes — protected by OrgPortal's own EUP session check ────
Route::group([
    'prefix'    => $subdirectory . 'portal/org',
    'middleware' => ['web'],
    'namespace'  => 'Modules\OrgPortal\Http\Controllers',
], function () {

    Route::get('company-tickets', 'OrgPortalFrontController@companyTickets')
        ->name('orgportal.portal.company-tickets');

    Route::get('tickets/{id}', 'OrgPortalFrontController@viewTicket')
        ->name('orgportal.portal.ticket');

    Route::post('tickets/{id}/reply', 'OrgPortalFrontController@replyTicket')
        ->name('orgportal.portal.ticket.reply');

    Route::get('settings', 'OrgPortalFrontController@settings')
        ->name('orgportal.portal.settings');

    Route::post('settings', 'OrgPortalFrontController@saveSettings')
        ->name('orgportal.portal.settings.save');
});
