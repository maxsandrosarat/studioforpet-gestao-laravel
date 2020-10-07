<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendaServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venda_servicos', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor',6,2)->default(0);
            $table->decimal('desconto',6,2)->default(0);
            $table->string('observacao')->nullable();
            $table->string('forma_pagamento');
            $table->foreignId('servico_id')->constrained()->cascadeOnDelete();//OBRIGATÓRIO, USAR DESSA FORMA
            $table->unsignedBigInteger('pet_id')->nullable();//NÃO OBRIGATÓRIO, USAR DESSA FORMA
            $table->foreign('pet_id')->references('id')->on('pets');
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
        Schema::dropIfExists('venda_servicos');
    }
}
