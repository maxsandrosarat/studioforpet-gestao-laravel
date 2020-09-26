<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->nullable();
            $table->string('nome');
            $table->unsignedBigInteger('raca_id');
            $table->enum('porte',['PEQUENO','MEDIO','GRANDE']);
            $table->enum('pelo',['CURTO','MEDIANO','LONGO']);
            $table->string('cor');
            $table->enum('sexo',['MACHO','FEMEA']);
            $table->boolean('ativo')->default(true);
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('raca_id')->references('id')->on('racas');
            $table->foreign('cliente_id')->references('id')->on('clientes');
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
        Schema::dropIfExists('pets');
    }
}
