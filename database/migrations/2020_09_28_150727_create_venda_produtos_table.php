<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendaProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venda_produtos', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor',6,2)->default(0);
            $table->decimal('desconto',6,2)->default(0);
            $table->string('observacao')->nullable();
            $table->string('forma_pagamento');
            $table->foreignId('produto_id')->constrained()->cascadeOnDelete();//OBRIGATÓRIO, USAR DESSA FORMA
            $table->unsignedBigInteger('cliente_id')->nullable();//NÃO OBRIGATÓRIO, USAR DESSA FORMA
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
        Schema::dropIfExists('venda_produtos');
    }
}
