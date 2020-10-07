<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLancamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lancamentos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo',['retirada','deposito']);
            $table->float('valor');
            $table->string('usuario');
            $table->string('motivo')->nullable();
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
        Schema::dropIfExists('lancamentos');
    }
}
