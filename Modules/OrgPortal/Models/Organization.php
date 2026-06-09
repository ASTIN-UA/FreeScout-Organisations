<?php

namespace Modules\OrgPortal\Models;

use Illuminate\Database\Eloquent\Model;
use App\Customer;

class Organization extends Model
{
    protected $table = 'organizations';

    protected $fillable = ['name', 'color', 'mailbox_id'];

    /**
     * Default badge color (matches FreeScout built-in .fs-tag gray).
     */
    const DEFAULT_COLOR = '#9eaab5';

    /**
     * Resolve the badge color, falling back to the default gray.
     */
    public function getBadgeColor(): string
    {
        return $this->color ?: self::DEFAULT_COLOR;
    }

    /**
     * Compute a slightly darker border/hover color from a hex color.
     */
    public static function darkenColor(string $hex, float $factor = 0.85): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        if (strlen($hex) !== 6) {
            return '#' . $hex;
        }
        $r = max(0, min(255, (int) round(hexdec(substr($hex, 0, 2)) * $factor)));
        $g = max(0, min(255, (int) round(hexdec(substr($hex, 2, 2)) * $factor)));
        $b = max(0, min(255, (int) round(hexdec(substr($hex, 4, 2)) * $factor)));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    public function mailbox()
    {
        return $this->belongsTo(\App\Mailbox::class);
    }

    /**
     * Organizations visible in a given mailbox: globally-scoped (mailbox_id IS NULL)
     * plus those explicitly assigned to that mailbox.
     */
    public function scopeVisibleInMailbox($query, int $mailboxId)
    {
        return $query->where(function ($q) use ($mailboxId) {
            $q->whereNull('mailbox_id')->orWhere('mailbox_id', $mailboxId);
        });
    }

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
