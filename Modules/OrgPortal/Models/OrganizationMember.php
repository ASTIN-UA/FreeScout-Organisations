<?php

namespace Modules\OrgPortal\Models;

use Illuminate\Database\Eloquent\Model;
use App\Customer;

class OrganizationMember extends Model
{
    protected $table = 'organization_members';

    protected $fillable = [
        'organization_id',
        'customer_id',
        'role',
        'notify_on_new_ticket',
    ];

    protected $casts = [
        'notify_on_new_ticket' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }
}
