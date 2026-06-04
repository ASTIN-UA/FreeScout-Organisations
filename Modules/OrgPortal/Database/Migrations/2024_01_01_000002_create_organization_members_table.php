<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationMembersTable extends Migration
{
    public function up()
    {
        Schema::create('organization_members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('organization_id');
            $table->unsignedInteger('customer_id');
            $table->enum('role', ['member', 'manager'])->default('member');
            $table->boolean('notify_on_new_ticket')->default(false);
            $table->timestamps();

            $table->foreign('organization_id')
                  ->references('id')->on('organizations')
                  ->onDelete('cascade');

            $table->foreign('customer_id')
                  ->references('id')->on('customers')
                  ->onDelete('cascade');

            $table->unique(['organization_id', 'customer_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('organization_members');
    }
}
