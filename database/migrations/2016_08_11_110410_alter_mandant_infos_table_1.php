<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMandantInfosTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mandant_infos', function (Blueprint $table) {
            $table->string('angemeldet_am');
            $table->string('umgemeldet_am');
            $table->string('abgemeldet_am');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mandant_infos', function (Blueprint $table) {
            //
        });
    }
}
