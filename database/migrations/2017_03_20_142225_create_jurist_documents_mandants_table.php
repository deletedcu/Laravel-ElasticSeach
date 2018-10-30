<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuristDocumentsMandantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurist_document_mandants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jurist_file_id')->unsigned();
            $table->integer('mandant_id')->unsigned();
            $table->timestamps();
        });
        
        Schema::table('jurist_document_mandants', function (Blueprint $table) {
            $table->foreign('jurist_file_id')
                ->references('id')
                ->on('jurist_files');
        });
        
        Schema::table('jurist_document_mandants', function (Blueprint $table) {
            $table->foreign('mandant_id')
                ->references('id')
                ->on('mandants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jurist_document_mandants');
    }
}
