<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgPortalNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('org_portal_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('conversation_id');
            $table->unsignedInteger('thread_id')->nullable();
            $table->string('type', 32); // new_ticket, new_reply, customer_reply
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['customer_id', 'read_at']);
            $table->index('conversation_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('org_portal_notifications');
    }
}
