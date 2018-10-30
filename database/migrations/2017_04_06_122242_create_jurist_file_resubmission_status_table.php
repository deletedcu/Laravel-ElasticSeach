<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuristFileResubmissionStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurist_file_resubmission_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
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
        Schema::drop('jurist_file_resubmission_status', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::drop('jurist_file_resubmission_status');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        });
    }
}
