<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lead_id');
            $table->enum('request_type',['resale','rental','new_home','land']);
            $table->enum('unit_type',['personal','commercial','land']);
            $table->unsignedInteger('unit_type_id');
            $table->string('price_from');
            $table->string('price_to');
            $table->unsignedInteger('date');
            $table->unsignedInteger('down_payment');
            $table->unsignedInteger('area_from');
            $table->unsignedInteger('area_to');
            $table->unsignedInteger('rooms_from')->nullable();
            $table->unsignedInteger('rooms_to')->nullable();
            $table->unsignedInteger('bathrooms_from')->nullable();
            $table->unsignedInteger('bathrooms_to')->nullable();
            $table->unsignedInteger('location');
            $table->unsignedInteger('user_id');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('requests');
    }
}
