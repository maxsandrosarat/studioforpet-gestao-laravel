<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->date("data");
            $table->time("hora", 0);
            $table->foreignId('servico_id')->constrained()->cascadeOnDelete();//OBRIGATÓRIO, USAR DESSA FORMA
            $table->decimal('valor',6,2)->default(0);
            $table->boolean('pet_cadastrado')->default(false);
            $table->unsignedBigInteger('pet_id')->nullable();//NÃO OBRIGATÓRIO, USAR DESSA FORMA
            $table->foreign('pet_id')->references('id')->on('pets');
            $table->string('nome_cliente')->nullable();
            $table->string('nome_pet')->nullable();
            $table->string('telefone')->nullable();
            $table->boolean('buscar')->default(false);
            $table->string('cep')->nullable();
            $table->string('rua')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->string('observacao')->nullable();
            $table->enum('status',['PENDENTE','ATENDIDO','CANCELADO']);
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
        Schema::dropIfExists('agendamentos');
    }
}
