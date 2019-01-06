<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClosedDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closed_deals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('proposal_id');
            $table->unsignedInteger('price');
            $table->string('agent_commission');
            $table->string('company_commission');
            $table->unsignedInteger('broker_commission')->nullable();
            $table->unsignedInteger('seller_id');
            $table->unsignedInteger('buyer_id');
            $table->text('description');
            $table->unsignedInteger('agent_id');
            $table->unsignedInteger('user_id');
            $table->enum('agent_payment_status',['payed','pending']);
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
        Schema::dropIfExists('closed_deals');
    }
}
