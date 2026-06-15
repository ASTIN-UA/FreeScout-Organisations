<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocaleToOrganizationMembersTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('organization_members', 'locale')) return;
        Schema::table('organization_members', function (Blueprint $table) {
            $table->string('locale', 8)->nullable()->after('is_active');
        });
    }

    public function down()
    {
        Schema::table('organization_members', function (Blueprint $table) {
            $table->dropColumn('locale');
        });
    }
}
