<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMailboxIdToOrganizationsTable extends Migration
{
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->unsignedInteger('mailbox_id')->nullable()->default(null)->after('id');
            $table->index('mailbox_id');
        });
    }

    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropIndex(['mailbox_id']);
            $table->dropColumn('mailbox_id');
        });
    }
}
