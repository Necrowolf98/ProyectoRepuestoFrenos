<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescripcionRepuestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descripcion_repuestos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('repuestofreno_id');
            $table->foreign('repuestofreno_id')->references('id')->on('repuesto_frenos')->onDelete('cascade');

            $table->string('clase');
            $table->string('medidas');
            $table->string('posicion');
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
        Schema::dropIfExists('descripcion_repuestos');
    }
}
