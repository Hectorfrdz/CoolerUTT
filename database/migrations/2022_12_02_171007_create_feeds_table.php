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
        Schema::create('feeds', function (Blueprint $table) {
            $table->id();
            $table->string("name",250);
            $table->string("description",250);
            $table->boolean("enabled");
            $table->unsignedBigInteger('group');
            $table->unsignedBigInteger('car');
            
            $table->foreign('group')->references('id')->on('groups');
            $table->foreign('car')->references('id')->on('cars');
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
        Schema::dropIfExists('feeds');
    }
};
