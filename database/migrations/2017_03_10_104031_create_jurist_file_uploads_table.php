<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuristFileUploadsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jurist_file_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name');
            $table->integer('jurist_file_id')->unsigned(); //Fk
            $table->timestamps();
        });

        Schema::table('jurist_file_uploads', function (Blueprint $table) {
            $table->foreign('jurist_file_id')
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
        Schema::drop('jurist_file_uploads');
    }
}
