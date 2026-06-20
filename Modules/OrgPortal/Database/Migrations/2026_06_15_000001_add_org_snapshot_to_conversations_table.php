<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrgSnapshotToConversationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('conversations', 'org_id')) {
            Schema::table('conversations', function (Blueprint $table) {
                $table->unsignedInteger('org_id')->nullable()->after('customer_id');
            });
        }
        if (!Schema::hasColumn('conversations', 'org_unit_id')) {
            Schema::table('conversations', function (Blueprint $table) {
                $table->unsignedInteger('org_unit_id')->nullable()->after('org_id');
            });
        }
        if (!Schema::hasColumn('conversations', 'org_attributed_at')) {
            Schema::table('conversations', function (Blueprint $table) {
                $table->timestamp('org_attributed_at')->nullable()->after('org_unit_id');
            });
        }
        // Add indexes — ignore if already exist
        try {
            Schema::table('conversations', function (Blueprint $table) {
                $table->index(['org_id', 'org_unit_id']);
            });
        } catch (\Exception $e) {}
        try {
            Schema::table('conversations', function (Blueprint $table) {
                $table->index('org_attributed_at');
            });
        } catch (\Exception $e) {}
    }

    public function down()
    {
        Schema::table('conversations', function (Blueprint $table) {
            try { $table->dropIndex(['org_id', 'org_unit_id']); } catch (\Exception $e) {}
            try { $table->dropIndex(['org_attributed_at']); } catch (\Exception $e) {}
        });
        $toDrop = array_filter(
            ['org_id', 'org_unit_id', 'org_attributed_at'],
            fn ($col) => Schema::hasColumn('conversations', $col)
        );
        if ($toDrop) {
            Schema::table('conversations', function (Blueprint $table) use ($toDrop) {
                $table->dropColumn(array_values($toDrop));
            });
        }
    }
}
