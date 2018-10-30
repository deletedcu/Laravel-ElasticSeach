<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMandantsTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mandants', function (Blueprint $table) {
            $table->string('geschaftsfuhrer');
            $table->string('geschaftsfuhrer_infos');
            $table->string('geschaftsfuhrer_von');
            $table->string('geschaftsfuhrer_bis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mandants', function (Blueprint $table) {
            //
        });
    }
}
