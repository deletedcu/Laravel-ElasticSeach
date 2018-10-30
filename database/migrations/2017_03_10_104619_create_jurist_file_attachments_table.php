<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuristFileAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jurist_file_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jurist_file_id')->unsigned(); //Fk
            $table->integer('jurist_file_attached_id')->unsigned(); //Fk
              $table->boolean('original')->default(0);
            $table->timestamps();
        });

        Schema::table('jurist_file_attachments', function (Blueprint $table) {
            $table->foreign('jurist_file_id')
                ->references('id')
                ->on('jurist_files')
                ->onUpdate('cascade');
        });

        Schema::table('jurist_file_attachments', function (Blueprint $table) {
            $table->foreign('jurist_file_attached_id')
                ->references('id')
                ->on('jurist_files')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('jurist_file_attachments');
    }
}
