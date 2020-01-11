<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKitSuplementos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suplementos', function (Blueprint $tabla){
            $tabla->unsignedInteger('kit_id')->index();

            $tabla->foreign('kit_id')->references('id')->on('kits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suplementos',function(Blueprint $table) {
            $table->dropForeign('suplementos_kit_id_foreign');
        });
    }
}
