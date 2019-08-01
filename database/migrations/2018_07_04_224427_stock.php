<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Stock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock', function(Blueprint $table) {
            $table->increments('id');

            $table->integer('productos_id')->unsigned();
            $table->foreign('productos_id')->references('id')->on('productos')->onDelete('restrict')->onUpdate('restrict');

            $table->integer('proveedores_id')->unsigned();
            $table->foreign('proveedores_id')->references('id')->on('proveedores')->onDelete('restrict')->onUpdate('restrict');
            $table->string('serial', 100)->unique()->nullable();
            $table->decimal('precio_entrada',8,0)->nullable();
            $table->decimal('precio_salida',8,0)->nullable();
            $table->date('fecha_entrada')->nullable();
            $table->date('fecha_salida')->nullable();
            $table->boolean('disponible')->default(true);
            
            $table->integer('clientes_id')->nullable()->unsigned();
            $table->foreign('clientes_id')->references('id')->on('clientes')->onDelete('restrict')->onUpdate('restrict');

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
        Schema::dropIfExists('stock');
    }
}
