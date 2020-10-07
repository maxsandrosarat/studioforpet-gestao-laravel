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
            $table->foreignId('raca_id')->constrained()->cascadeOnDelete();
            $table->enum('porte',['PEQUENO','MEDIO','GRANDE']);
            $table->enum('pelo',['CURTO','MEDIANO','LONGO']);
            $table->string('cor');
            $table->enum('sexo',['MACHO','FEMEA']);
            $table->boolean('ativo')->default(true);
            $table->foreignId('cliente_id')->constrained()->cascadeOnDelete();
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
