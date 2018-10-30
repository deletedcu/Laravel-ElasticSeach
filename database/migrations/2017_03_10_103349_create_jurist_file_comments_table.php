<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuristFileCommentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jurist_file_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jurist_file_id')->unsigned(); //Fk
            $table->integer('user_id')->unsigned(); //Fk
            $table->string('title');
            $table->text('comment');
            $table->timestamps();
        });

        Schema::table('jurist_file_comments', function (Blueprint $table) {
            $table->foreign('jurist_file_id')
                ->references('id')
                ->on('jurist_files')
                ->onUpdate('cascade');
        });
        Schema::table('jurist_file_comments', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('jurist_file_comments');
    }
}
