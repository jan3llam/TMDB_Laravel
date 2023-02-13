<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateFavQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fav_quotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('guest_id');
            $table->foreign('guest_id')->references('id')->on('guests');
            $table->string('anime',60);
            $table->string('character',60);
            $table->string('quote',249);
            $table->unique(['quote','guest_id']);
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
        Schema::dropIfExists('fav_quotes');
    }
}
