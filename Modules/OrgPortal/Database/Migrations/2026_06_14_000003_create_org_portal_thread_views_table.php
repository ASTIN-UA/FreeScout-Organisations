<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgPortalThreadViewsTable extends Migration
{
    public function up()
    {
        Schema::create('org_portal_thread_views', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('thread_id');
            $table->unsignedInteger('conversation_id');
            $table->unsignedInteger('customer_id');
            $table->timestamp('viewed_at');

            $table->unique(['thread_id', 'customer_id']);
            $table->index('conversation_id');
            $table->index('customer_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('org_portal_thread_views');
    }
}
