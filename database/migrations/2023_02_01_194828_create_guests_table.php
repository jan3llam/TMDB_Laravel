<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('firstname',60);
            $table->string('lastname',60);
            $table->string('username',100)->unique();
            $table->string('email',100)->unique();
            $table->string('password',60);
            $table->string('avatar',100)->nullable();
            $table->string('session',100)->nullable();
            $table->timestamp('session_expiry')->nullable();
            $table->boolean('verified')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('quote_limit')->default(4);
            $table->string('google_id',100)->unique()->nullable();
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
        Schema::dropIfExists('guests');
    }
}
