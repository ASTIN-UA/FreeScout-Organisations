<?php

namespace Modules\OrgPortal\Models;

use Illuminate\Database\Eloquent\Model;
use App\Customer;

class Organization extends Model
{
    protected $table = 'organizations';

    protected $fillable = ['name'];

    public function members()
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'organization_members', 'organization_id', 'customer_id')
            ->withPivot('role', 'notify_on_new_ticket')
            ->withTimestamps();
    }

    public function managers()
    {
        return $this->members()->where('role', 'manager');
    }

    /**
     * Find the organization a customer belongs to (first match).
     */
    public static function forCustomer(int $customerId): ?self
    {
        $member = OrganizationMember::where('customer_id', $customerId)->first();
        return $member ? $member->organization : null;
    }
}
