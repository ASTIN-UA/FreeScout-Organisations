<?php

namespace Modules\OrgPortal\Models;

use Illuminate\Database\Eloquent\Model;
use App\Customer;

class OrganizationMember extends Model
{
    protected $table = 'organization_members';

    protected $fillable = [
        'organization_id',
        'unit_id',
        'customer_id',
        'role',
        'can_manage_org',
        'notify_on_new_ticket',
        'is_active',
        'deactivated_at',
        'locale',
        'source',
    ];

    protected $casts = [
        'notify_on_new_ticket' => 'boolean',
        'can_manage_org'       => 'boolean',
        'is_active'            => 'boolean',
        'deactivated_at'       => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function unit()
    {
        return $this->belongsTo(OrganizationUnit::class, 'unit_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Only active memberships.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /**
     * Global manager: a manager not scoped to any structural unit — sees the
     * whole organization.
     */
    public function isGlobalManager(): bool
    {
        return $this->role === 'manager' && $this->unit_id === null;
    }

    /**
     * Unit manager: a manager scoped to a single structural unit.
     */
    public function isUnitManager(): bool
    {
        return $this->role === 'manager' && $this->unit_id !== null;
    }

    /**
     * Whether this membership was created automatically by domain matching
     * rather than by a human. Drives the "@" badge in the members list and
     * lets a mistyped domain be rolled back.
     */
    public function isAutomatic(): bool
    {
        return $this->source === \Modules\OrgPortal\Services\MembershipService::SOURCE_DOMAIN;
    }
}
