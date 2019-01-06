<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('en_name');
            $table->string('ar_name');
            $table->string('logo');
            $table->string('cover');
            $table->text('en_description')->nullable();
            $table->text('ar_description')->nullable();
            $table->double('meter_price');
            $table->double('area');
            $table->double('lat');
            $table->double('lng');
            $table->integer('zoom');
            $table->unsignedInteger('developer_id');
            $table->unsignedInteger('location_id');
            $table->double('down_payment');
            $table->double('installment_year');
            $table->string('commission')->nullable();
            $table->string('video')->nullable();
            $table->string('website_cover')->nullable();
            $table->boolean('featured')->defualt(0);
            $table->boolean('show_website')->defualt(0);
            $table->unsignedInteger('priority')->defualt(0);
			$table->unsignedInteger('user_id');
            $table->string('facebook')->nullable();
            $table->boolean('on_slider');
            $table->text('watermarked_image');
            $table->text('resale_units')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description	')->nullable();
            $table->string('map_marker')->nullable();
            $table->text('website_slider	')->nullable();
            $table->enum('type',['commercial','personal']);
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
        Schema::dropIfExists('projects');
    }
}
