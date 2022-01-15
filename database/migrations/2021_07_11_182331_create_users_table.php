<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->unsignedBigInteger('people_id');
            $table->foreign('people_id')->references('id')->on('people')->onDelete('cascade');

            $table->string('username')->unique();
            $table->string('password');
            $table->enum('state', ['A', 'I'])->nullable()->default('A');

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
        Schema::dropIfExists('users');
    }
}
