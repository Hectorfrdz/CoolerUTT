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
            $table->unsignedBigInteger('user');
            $table->unsignedBigInteger('type_car');
            
            $table->foreign('user')->references('id')->on('users');
            $table->foreign('type_car')->references('id')->on('type_car');
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
