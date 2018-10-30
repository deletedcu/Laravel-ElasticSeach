<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuristFilesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jurist_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('mandant_id')->unsigned(); //Fk
            $table->integer('user_id')->unsigned(); //Fk
            $table->integer('document_status_id')->unsigned(); //Fk
            $table->timestamps();
        });

        Schema::table('jurist_files', function (Blueprint $table) {
            $table->foreign('mandant_id')
                ->references('id')
                ->on('mandants')
                ->onUpdate('cascade');
        });

        Schema::table('jurist_files', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');
        });

        Schema::table('jurist_files', function (Blueprint $table) {
            $table->foreign('document_status_id')
                ->references('id')
                ->on('document_statuses')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('jurist_files');
    }
}
