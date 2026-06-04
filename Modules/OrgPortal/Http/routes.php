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
