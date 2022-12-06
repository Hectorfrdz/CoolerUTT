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
        Schema::table('users', function (Blueprint $table) {
            $table->string('lastname',250);
            $table->unsignedBigInteger('rol_id')->default(1);
            $table->integer('status')->default(0);
            $table->foreign('rol_id')->references('id')->on('roles');
            $table->bigInteger('phone');
            $table->string('red',250);
            $table->string('contrasena_red',250);
            $table->string('Username',250);
            $table->string('Active_Key',250);
            $table->bigInteger('verificationCode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->removeColumn('rol_id');
            $table->removeColumn('status');
        });
    }
};
