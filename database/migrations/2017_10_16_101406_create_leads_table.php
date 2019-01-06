<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('prefix_name',['mr','mrs','ms']);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('ar_first_name')->nullable();
            $table->string('ar_last_name')->nullable();
            $table->string('ar_middle_name')->nullable();
            $table->string('email')->nullable();
            $table->text('password')->nullable();
            $table->text('other_emails')->nullable();
            $table->string('phone')->unique();
            $table->string('club')->nullable();
            $table->enum('religion',['muslim','christian','jewish','other'])->nullable();
            $table->integer('birth_date')->nullable();
            $table->text('social')->nullable();
            $table->text('other_phones')->nullable();
            $table->text('address')->nullable();
            $table->unsignedInteger('title_id')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->unsignedInteger('nationality')->nullable();
            $table->unsignedInteger('lead_source_id');
            $table->unsignedInteger('industry_id')->nullable();
            $table->unsignedInteger('campain_id')->nullable();
            $table->string('company')->nullable();
            $table->string('school')->nullable();
            $table->string('image')->nullable();
            $table->text('notes')->nullable();
            $table->string('facebook')->unique()->nullable();
            $table->enum('status',['new','unqualified','working'])->nullable();
            $table->boolean('confirm')->default(false);
            $table->boolean('favorite')->default(false);
            $table->boolean('hot')->default(false);
            $table->bigInteger('id_number')->nullable();
            $table->unsignedInteger('agent_id');
            $table->unsignedInteger('user_id');
            $table->text('refresh_token')->nullable();
			$table->rememberToken();
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
        Schema::dropIfExists('leads');
    }
}
