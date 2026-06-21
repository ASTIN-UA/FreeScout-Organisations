<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorToOrganizationsTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('organizations', 'color')) return;
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('color', 20)->nullable()->default(null)->after('name');
        });
    }

    public function down()
    {
        if (!Schema::hasColumn('organizations', 'color')) return;
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
}
