<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->string('permalink');
            $table->string('raw_address');
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('phone');
            $table->text('description');
            $table->text('description_plaintext');
            $table->timestamp('date_added');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('restaurants');
    }
}
