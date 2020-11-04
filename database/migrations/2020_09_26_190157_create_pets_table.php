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
            $table->enum('pelo',['CURTO','MEDIANO','LONGO','TOSADO']);
            $table->string('cor');
            $table->enum('sexo',['MACHO','FEMEA']);
            $table->foreignId('cliente_id')->constrained()->cascadeOnDelete();
            $table->boolean('temPlano')->default(false);
            $table->unsignedBigInteger('plano_id')->nullable();//NÃO OBRIGATÓRIO, USAR DESSA FORMA
            $table->foreign('plano_id')->references('id')->on('planos');
            $table->float('valorPlano')->default(0);
            $table->boolean('planoCancelado')->default(false);
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
