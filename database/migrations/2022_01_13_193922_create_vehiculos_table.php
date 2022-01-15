<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->unsignedBigInteger('repuestofreno_id');
            $table->foreign('repuestofreno_id')->references('id')->on('repuesto_frenos')->onDelete('cascade');

            $table->unsignedBigInteger('casa_marca_id');
            $table->foreign('casa_marca_id')->references('id')->on('marcas');

            $table->string('modelo');
            $table->string('anio_vehiculo');

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
        Schema::dropIfExists('vehiculos');
    }
}
