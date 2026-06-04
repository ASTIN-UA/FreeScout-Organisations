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
        $organizations = Organization::orderBy('name')->paginate(20);

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
            ->with('flash_success', __('Organization created.'));
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
            ->with('flash_success', __('Organization updated.'));
    }

    public function destroy(int $id)
    {
        Organization::findOrFail($id)->delete();

        return redirect()->route('orgportal.admin.index')
            ->with('flash_success', __('Organization deleted.'));
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
                ->with('flash_error', __('This customer is already a member of the organization.'));
        }

        // One org per customer — check they're not in another org
        if (OrganizationMember::where('customer_id', $customerId)->exists()) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('This customer already belongs to another organization.'));
        }

        OrganizationMember::create([
            'organization_id' => $id,
            'customer_id'     => $customerId,
            'role'            => $request->input('role'),
        ]);

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('Member added.'));
    }

    public function removeMember(int $id, int $memberId)
    {
        Organization::findOrFail($id);
        OrganizationMember::where('id', $memberId)
            ->where('organization_id', $id)
            ->firstOrFail()
            ->delete();

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('Member removed.'));
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
