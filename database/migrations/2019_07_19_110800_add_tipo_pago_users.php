<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoPagoUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tipo_pago',20)->nullable();
        });
        Schema::table('contactos', function (Blueprint $table) {
            $table->string('order_id',40)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tipo_pago');
        });
    }
}
