<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('en_name');
            $table->string('ar_name');
            $table->unsignedInteger('unit_id');
            $table->unsignedInteger('lead_id')->default(0);
            $table->double('start_price');
            $table->double('meter_price')->nullable();
            $table->double('area_from');
            $table->double('area_to')->nullable();
            $table->enum('type',['commercial','personal']);
            $table->text('en_description')->nullable();
            $table->text('ar_description')->nullable();
            $table->string('main')->nullable();
            $table->enum('availability',['available','sold'])->default('available');
            $table->unsignedInteger('phase_id');
            $table->unsignedInteger('user_id');
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
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
        Schema::dropIfExists('properties');
    }
}
