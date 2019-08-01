<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Productos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tipos_id')->unsigned();
            $table->foreign('tipos_id')->references('id')->on('tipos_de_productos')->onDelete('restrict')->onUpdate('restrict');
            $table->integer('marcas_id')->unsigned();
            $table->foreign('marcas_id')->references('id')->on('marcas')->onDelete('restrict')->onUpdate('restrict');
            $table->string('modelo');
            $table->string('upc', 80)->unique()->nullable()->default(null);
            $table->string('ean', 80)->unique()->nullable()->default(null);
            $table->boolean('serializado')->default(true);
            $table->boolean('estado')->default(true);
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
        Schema::dropIfExists('productos');
    }
}
