<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_facilities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('unit_id');
			$table->unsignedInteger('facility_id');
			$table->enum('type',['project','resale','rental']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit_facilities');
    }
}
