<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJuristFileResubmission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        
        Schema::table('jurist_file_resubmissions', function (Blueprint $table) {
             
             $table->dropForeign(['document_status_id']);
             $table->dropColumn('document_status_id');
             
             $table->integer('jurist_file_resubmission_status_id')->after('jurist_resubmission_priority_id')->unsigned();
             $table->foreign('jurist_file_resubmission_status_id', 'jfr_status_id_foreign')
                  ->references('id')
                  ->on('jurist_file_resubmission_status')
                  ->onDelete('cascade');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jurist_file_resubmissions', function (Blueprint $table) {
            //
        });
    }
}
