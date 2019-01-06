<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('logo');
            $table->string('title');
            $table->string('admin_path');
            $table->enum('theme',['skin-blue','skin-black','skin-purple','skin-green','skin-red','skin-yellow','skin-blue-light','skin-black-light','skin-purple-light','skin-green-light','skin-red-light','skin-yellow-light']);
            $table->double('lat');
            $table->double('lng');
            $table->unsignedInteger('zoom');
            $table->text('get_in_touch');
            $table->text('ar_get_in_touch');
            $table->text('address');
            $table->text('ar_address');
            $table->string('email');
            $table->string('cover');
            $table->text('about_hub');
            $table->text('mission');
            $table->text('vision');
            $table->text('watermark');
            $table->text('ar_about_hub');
            $table->text('ar_mission');
            $table->text('ar_vision');
            $table->text('seo');
            $table->integer('refresh_sitemap')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
