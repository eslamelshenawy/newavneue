<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('personal_commercial',['personal','commercial']);
            $table->enum('unit_type',['resale','rental','new_home','land']);
            $table->unsignedInteger('unit_id');
            $table->unsignedInteger('lead_id');
            $table->text('description');
            $table->string('price');
            $table->text('file')->nullable();
            $table->unsignedInteger('user_id');
            $table->enum('status',['pending','confirmed']);
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
        Schema::dropIfExists('proposals');
    }
}
