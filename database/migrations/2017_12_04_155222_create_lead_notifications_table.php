<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type',['events','projects','others']);
            $table->integer('type_id')->default(0);
            $table->string('ar_title');
            $table->string('en_title');
            $table->text('ar_body');
            $table->text('en_body');
            $table->integer('lead_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('lead_notifications');
    }
}
