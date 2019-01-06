<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('value');
            $table->integer('currency_id');
            $table->text('description')->nullable();
            $table->text('name');
            $table->text('date');
            $table->integer('safe_id')->nullable();
            $table->integer('bank_id')->nullable();
            $table->enum('status',['collected','not_collected']);
            $table->enum('payment_method',['cash','cheques','voucher','bank_transfer']);
            $table->enum('source',['closed_deal','manual']);
            $table->integer('closed_deal_id')->nullable();
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
        Schema::dropIfExists('incomes');
    }
}
