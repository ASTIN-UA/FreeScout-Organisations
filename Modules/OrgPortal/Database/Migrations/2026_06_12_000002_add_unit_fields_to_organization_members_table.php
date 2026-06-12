<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitFieldsToOrganizationMembersTable extends Migration
{
    public function up()
    {
        Schema::table('organization_members', function (Blueprint $table) {
            $table->unsignedInteger('unit_id')->nullable()->default(null)->after('organization_id');
            $table->boolean('can_manage_org')->default(false)->after('role');
            $table->boolean('is_active')->default(true)->after('notify_on_new_ticket');
            $table->timestamp('deactivated_at')->nullable()->default(null)->after('is_active');

            $table->foreign('unit_id')
                  ->references('id')->on('organization_units')
                  ->onDelete('set null');

            $table->index('unit_id');
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
