<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuristFileResubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurist_file_resubmissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jurist_file_id')->unsigned(); //Fk
            $table->integer('sender_id')->unsigned(); //Fk
            $table->integer('reciever_id')->unsigned(); //Fk
            $table->integer('jurist_resubmission_priority_id')->unsigned(); //Fk
            $table->integer('document_status_id')->unsigned(); //Fk
            $table->text('status_reason')->nullable();
            $table->timestamp('date_available'); //Fk
            $table->timestamps();
        });

        Schema::table('jurist_file_resubmissions', function (Blueprint $table) {
            $table->foreign('jurist_file_id')
                ->references('id')
                ->on('jurist_files');
        });
        Schema::table('jurist_file_resubmissions', function (Blueprint $table) {
            $table->foreign('sender_id')
                ->references('id')
                ->on('users');
        });
        Schema::table('jurist_file_resubmissions', function (Blueprint $table) {
            $table->foreign('reciever_id')
                ->references('id')
                ->on('users');
        });
        Schema::table('jurist_file_resubmissions', function (Blueprint $table) {
            $table->foreign('document_status_id')
                ->references('id')
                ->on('document_statuses');
        });
        Schema::table('jurist_file_resubmissions', function (Blueprint $table) {
            $table->foreign('jurist_resubmission_priority_id','jfr_jrp_fk')
                ->references('id')
                ->on('jurist_resubmission_priorities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jurist_file_resubmissions');
    }
}
