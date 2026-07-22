<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSourceToOrganizationMembersTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('organization_members', 'source')) {
            Schema::table('organization_members', function (Blueprint $table) {
                // How this membership came to exist. Existing rows default to
                // 'manual' — every membership created before domain matching
                // shipped was made by a human.
                //
                // Without this column a mistyped domain cannot be undone: there
                // is no way to tell the hundreds of rows the automation created
                // from the ones an admin added by hand.
                $table->string('source', 20)->default('manual')->after('locale');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('organization_members', 'source')) {
            Schema::table('organization_members', function (Blueprint $table) {
                $table->dropColumn('source');
            });
        }
    }
}
