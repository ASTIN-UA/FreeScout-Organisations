<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationUnitsTable extends Migration
{
    public function up()
    {
        Schema::create('organization_units', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('organization_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('organization_id')
                  ->references('id')->on('organizations')
                  ->onDelete('cascade');

            $table->unique(['organization_id', 'name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('organization_units');
    }
}
