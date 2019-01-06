<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResaleUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resale_units', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type',['commercial','personal']);
            $table->unsignedInteger('unit_type_id')->nullable();
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedInteger('lead_id')->nullable();
            $table->unsignedInteger('original_price')->nullable();
            $table->unsignedInteger('payed')->nullable();
            $table->unsignedInteger('rest')->nullable();
            $table->unsignedInteger('total');
            $table->unsignedInteger('delivery_date')->nullable();
            $table->enum('finishing',['finished','semi_finished','not_finished']);
            $table->unsignedInteger('location')->nullable();
            $table->text('ar_notes')->nullable();
            $table->text('en_notes')->nullable();
            $table->text('ar_description');
            $table->text('en_description');
            $table->string('ar_title');
            $table->string('en_title');
            $table->string('ar_address');
            $table->string('en_address');
            $table->text('youtube_link')->nullable();
            $table->string('phone');
            $table->text('other_phones')->nullable();
            $table->unsignedInteger('area');
            $table->unsignedInteger('price');
            $table->unsignedInteger('rooms')->nullable();
            $table->unsignedInteger('bathrooms')->nullable();
            $table->unsignedInteger('floors')->nullable();
            $table->string('lng');
            $table->string('lat');
            $table->string('zoom');
            $table->text('image');
            $table->text('watermarked_image');
            $table->text('other_images')->nullable();
            $table->text('other_watermarked_images')->nullable();
            $table->enum('payment_method',['cash','installment','cash_or_installment']);
            $table->enum('view',['main_street','bystreet','garden','corner','sea_or_river']);
            $table->enum('availability',['available','sold']);
            $table->unsignedInteger('due_now');
            $table->unsignedInteger('broker')->nullable();
            $table->boolean('featured')->defualt(0);
            $table->unsignedInteger('priority')->defualt(0);
            $table->unsignedInteger('user_id');
            $table->integer('confirmed')->nullable();
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
        Schema::dropIfExists('resale_units');
    }
}
