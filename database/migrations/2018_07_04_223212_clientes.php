<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Clientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function(Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('correo')->unique()->nullable();
            $table->integer('documento')->unique()->nullable();
            $table->string('domicilio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
