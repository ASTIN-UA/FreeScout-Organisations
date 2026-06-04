<?php

namespace Modules\OrgPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
use Modules\OrgPortal\Models\Organization;
use Modules\OrgPortal\Models\OrganizationMember;

class OrgPortalAdminController extends Controller
{
    public function index()
    {
        $organizations = Organization::withCount('members')->orderBy('name')->paginate(20);

        return view('orgportal::admin.index', compact('organizations'));
    }

    public function create()
    {
        return view('orgportal::admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:organizations,name',
        ]);

        Organization::create(['name' => $request->input('name')]);

        return redirect()->route('orgportal.admin.index')
            ->with('flash_success', __('orgportal::messages.org_created'));
    }

    public function edit(int $id)
    {
        $organization = Organization::findOrFail($id);
        $members      = $organization->members()->with('customer')->get();

        return view('orgportal::admin.edit', compact('organization', 'members'));
    }

    public function update(Request $request, int $id)
    {
        $organization = Organization::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:organizations,name,' . $id,
        ]);

        $organization->update(['name' => $request->input('name')]);

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.org_updated'));
    }

    public function destroy(int $id)
    {
        Organization::findOrFail($id)->delete();

        return redirect()->route('orgportal.admin.index')
            ->with('flash_success', __('orgportal::messages.org_deleted'));
    }

    public function addMember(Request $request, int $id)
    {
        $organization = Organization::findOrFail($id);

        $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'role'        => 'required|in:member,manager',
        ]);

        $customerId = (int) $request->input('customer_id');

        // Prevent duplicate membership
        if (OrganizationMember::where('organization_id', $id)
            ->where('customer_id', $customerId)->exists()
        ) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.already_member'));
        }

        // One org per customer — check they're not in another org
        if (OrganizationMember::where('customer_id', $customerId)->exists()) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.already_in_org'));
        }

        try {
            OrganizationMember::create([
                'organization_id' => $id,
                'customer_id'     => $customerId,
                'role'            => $request->input('role'),
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Unique constraint: concurrent request already added this customer
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.already_member'));
        }

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.member_added'));
    }

    public function removeMember(int $id, int $memberId)
    {
        Organization::findOrFail($id);
        OrganizationMember::where('id', $memberId)
            ->where('organization_id', $id)
            ->firstOrFail()
            ->delete();

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.member_removed'));
    }

    public function settings()
    {
        return view('orgportal::admin.settings', [
            'show_badge_conversation' => (bool) \Option::get('orgportal.show_badge_conversation', true),
            'show_badge_kanban'       => (bool) \Option::get('orgportal.show_badge_kanban', true),
        ]);
    }

    public function saveSettings(Request $request)
    {
        \Option::set('orgportal.show_badge_conversation', (bool) $request->input('show_badge_conversation'));
        \Option::set('orgportal.show_badge_kanban', (bool) $request->input('show_badge_kanban'));

        return redirect()->route('orgportal.admin.settings')
            ->with('flash_success', __('orgportal::messages.settings_saved'));
    }

    /**
     * AJAX: search customers by name or email for the member-add form.
     */
    public function searchCustomers(Request $request)
    {
        $query = trim($request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $customers = Customer::where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->orWhereHas('emails', function ($q) use ($query) {
                $q->where('email', 'like', "%{$query}%");
            })
            ->orderBy('last_name')
            ->limit(15)
            ->get(['id', 'first_name', 'last_name']);

        return response()->json(
            $customers->map(fn ($c) => [
                'id'   => $c->id,
                'text' => $c->getFullName() . ' (#' . $c->id . ')',
            ])
        );
    }
}
