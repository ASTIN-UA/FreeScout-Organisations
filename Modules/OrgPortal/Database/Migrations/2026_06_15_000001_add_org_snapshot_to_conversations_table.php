<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrgSnapshotToConversationsTable extends Migration
{
    public function up()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->unsignedInteger('org_id')->nullable()->after('customer_id');
            $table->unsignedInteger('org_unit_id')->nullable()->after('org_id');
            // NULL = not yet attributed (backfill cursor). Non-null = snapshot stamped.
            $table->timestamp('org_attributed_at')->nullable()->after('org_unit_id');

            $table->index(['org_id', 'org_unit_id']);
            $table->index('org_attributed_at');
        });
    }

    public function down()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropIndex(['org_id', 'org_unit_id']);
            $table->dropIndex(['org_attributed_at']);
            $table->dropColumn(['org_id', 'org_unit_id', 'org_attributed_at']);
        });
    }
}
