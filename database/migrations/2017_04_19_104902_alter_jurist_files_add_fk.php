<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJuristFilesAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jurist_files', function (Blueprint $table) {
            $table->foreign('jurist_file_type_id','fk_jfti_jft')
            ->references('id')
            ->on('jurist_file_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jurist_files', function (Blueprint $table) {
            //
        });
    }
}
