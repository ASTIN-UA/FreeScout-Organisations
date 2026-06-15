<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitFieldsToOrganizationMembersTable extends Migration
{
    public function up()
    {
        Schema::table('organization_members', function (Blueprint $table) {
            if (!Schema::hasColumn('organization_members', 'unit_id')) {
                $table->unsignedInteger('unit_id')->nullable()->default(null)->after('organization_id');
                $table->foreign('unit_id')
                      ->references('id')->on('organization_units')
                      ->onDelete('set null');
                $table->index('unit_id');
            }
            if (!Schema::hasColumn('organization_members', 'can_manage_org')) {
                $table->boolean('can_manage_org')->default(false)->after('role');
            }
            if (!Schema::hasColumn('organization_members', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('notify_on_new_ticket');
            }
            if (!Schema::hasColumn('organization_members', 'deactivated_at')) {
                $table->timestamp('deactivated_at')->nullable()->default(null)->after('is_active');
            }
        });
    }

    public function down()
    {
        Schema::table('organization_members', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn(['unit_id', 'can_manage_org', 'is_active', 'deactivated_at']);
        });
    }
}
