<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentalUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rental_units', function (Blueprint $table) {
            $table->increments('id');
			$table->enum('type',['commercial','personal']);
			$table->unsignedInteger('unit_type_id')->nullable();
			$table->unsignedInteger('project_id')->nullable();
			$table->unsignedInteger('lead_id')->nullable();
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
			$table->unsignedInteger('rent');
			$table->unsignedInteger('rooms');
			$table->unsignedInteger('bathrooms');
			$table->unsignedInteger('floors')->nullable();
			$table->enum('availability',['available','solid']);
			$table->string('lng');
			$table->string('lat');
			$table->string('zoom');
			$table->text('image');
			$table->text('watermarked_image');
            $table->unsignedInteger('due_now');
			$table->unsignedInteger('user_id');
			$table->text('other_images')->nullable();
			$table->enum('payment_method',['cash','installment','cash_or_installment']);
			$table->enum('view',['main_street','bystreet','garden','corner','sea_or_river']);
            $table->unsignedInteger('broker')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
			$table->integer('confirmed')->nullable();
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
        Schema::dropIfExists('rental_units');
    }
}
