<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('rol',20);
            $table->string('referencia', 5)->nullable();
            $table->string('codigo', 5)->nullable();
            $table->date('inicio_reto')->nullable();
            $table->boolean('pagado')->default(0);
            $table->integer('ingresados')->default(0);
            $table->integer('ingresados_reto')->default(0);
            $table->decimal('saldo', 8,2)->default(0);
            $table->boolean('objetivo')->nullable();
            $table->boolean('genero')->nullable();
            $table->bigInteger('tarjeta')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
