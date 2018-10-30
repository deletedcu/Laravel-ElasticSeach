<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuristFileTypeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurist_file_type_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jurist_file_type_id')->nullable()->unsigned();
            $table->integer('user_id')->nullable()->unsigned();
            $table->timestamps();
        });
        
        Schema::table('jurist_file_type_users', function (Blueprint $table) {
            $table->foreign('jurist_file_type_id')
            ->references('id')
            ->on('jurist_file_types');
        });
        
        Schema::table('jurist_file_type_users', function (Blueprint $table) {
            $table->foreign('user_id')
            ->references('id')
            ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jurist_file_type_users');
    }
}
