<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespuestasUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('respuestas_usuarios', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('test_session_id');
        $table->unsignedBigInteger('test_id');
        $table->unsignedBigInteger('question_id');
        $table->string('respuesta')->nullable();
        $table->boolean('es_correcta')->default(0);
        $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('respuestas_usuarios');
    }
}
