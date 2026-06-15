<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationTagsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('organization_tags')) return;

        Schema::create('organization_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('organization_id');
            $table->unsignedInteger('tag_id');
            $table->unsignedInteger('unit_id')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')
                  ->references('id')->on('organizations')
                  ->onDelete('cascade');

            $table->foreign('unit_id')
                  ->references('id')->on('organization_units')
                  ->onDelete('set null');

            $table->unique(['organization_id', 'tag_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('organization_tags');
    }
}
