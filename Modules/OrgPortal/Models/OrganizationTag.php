<?php

namespace Modules\OrgPortal\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationTag extends Model
{
    protected $table    = 'organization_tags';
    protected $fillable = ['organization_id', 'tag_id', 'unit_id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function unit()
    {
        return $this->belongsTo(OrganizationUnit::class, 'unit_id');
    }

    /**
     * Find the org binding for a given tag_id (returns first match).
     */
    public static function findByTagId(int $tagId): ?self
    {
        return static::where('tag_id', $tagId)->first();
    }

    /**
     * All tag_ids bound to a specific organization.
     */
    public static function tagIdsForOrg(int $orgId): array
    {
        return static::where('organization_id', $orgId)->pluck('tag_id')->toArray();
    }
}
