<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJuristResubmissionPriorities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jurist_resubmission_priorities', function (Blueprint $table) {
            $table->string('bgcolor')->after('color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jurist_resubmission_priorities', function (Blueprint $table) {
            //
        });
    }
}
