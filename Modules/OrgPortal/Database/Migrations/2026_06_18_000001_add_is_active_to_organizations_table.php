<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveToOrganizationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('organizations', 'is_active')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('mailbox_id');
                $table->index('is_active');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('organizations', 'is_active')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropIndex(['is_active']);
                $table->dropColumn('is_active');
            });
        }
    }
}
