<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string("name",250);
            $table->string("description",250);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('type_car_id');
            $table->unsignedBigInteger('group_id');
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('type_car_id')->references('id')->on('type_car');
            $table->foreign('group_id')->references('id')->on('groups');
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
        Schema::dropIfExists('cars');
    }
};
