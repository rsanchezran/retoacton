<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMontoCompras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->decimal('monto',8,2)->change();
        });
        Schema::table('pagos', function (Blueprint $table) {
            $table->decimal('monto',10,2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compras', function (Blueprint $table) {
            Schema::table('compras', function (Blueprint $table) {
                $table->decimal('monto',6,2)->change();
            });
            Schema::table('pagos', function (Blueprint $table) {
                $table->decimal('monto',6,2)->change();
            });
        });
    }
}
