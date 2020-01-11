<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEtapaContactos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->tinyInteger('etapa')->nullable();
            $table->integer('peso')->nullable();
            $table->integer('ideal')->nullable();
            $table->string('medio')->nullable();
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
            $table->dropColumn('etapa');
            $table->dropColumn('peso');
            $table->dropColumn('ideal');
            $table->dropColumn('medio');
        });
    }
}
