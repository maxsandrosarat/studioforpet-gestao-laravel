<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->nullable();
            $table->foreignId('categoria_id')->constrained()->cascadeOnDelete();
            $table->string('nome');
            $table->foreignId('tipo_animal_id')->constrained()->cascadeOnDelete();
            $table->string('tipo_fase');
            $table->foreignId('marca_id')->constrained()->cascadeOnDelete();
            $table->string('embalagem');
            $table->float('preco');
            $table->integer('estoque');
            $table->boolean('ativo')->default(true);
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
        Schema::dropIfExists('produtos');
    }
}
