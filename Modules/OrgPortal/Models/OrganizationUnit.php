<?php

namespace Modules\OrgPortal\Models;

use Illuminate\Database\Eloquent\Model;
use App\Customer;

class OrganizationUnit extends Model
{
    protected $table = 'organization_units';

    protected $fillable = [
        'organization_id',
        'name',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function members()
    {
        return $this->hasMany(OrganizationMember::class, 'unit_id');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'organization_members', 'unit_id', 'customer_id')
            ->withPivot('role', 'notify_on_new_ticket', 'is_active')
            ->withTimestamps();
    }

    /**
     * Managers scoped to this unit.
     */
    public function managers()
    {
        return $this->members()->where('role', 'manager');
    }
}
