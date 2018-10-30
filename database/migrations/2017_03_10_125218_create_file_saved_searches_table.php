<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileSavedSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_saved_searches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jurist_file_id')->unsigned(); //Fk
            $table->integer('saved_search_id')->unsigned(); //Fk
            $table->boolean('original')->default(0);
            $table->timestamps();
        });

        Schema::table('file_saved_searches', function (Blueprint $table) {
            $table->foreign('jurist_file_id')
                ->references('id')
                ->on('jurist_files')
                ->onUpdate('cascade');
        });
        Schema::table('file_saved_searches', function (Blueprint $table) {
            $table->foreign('saved_search_id')
                ->references('id')
                ->on('saved_searches')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('file_saved_searches');
    }
}
