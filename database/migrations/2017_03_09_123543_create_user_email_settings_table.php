<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEmailSettingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_email_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned(); // FK
            $table->integer('document_type_id')->nullable()->unsigned();
            $table->integer('email_recievers_id')->nullable()->unsigned();
            $table->integer('sending_method')->nullable()->unsigned();
            $table->string('recievers_text', 256);
            $table->boolean('active')->default(1);
            $table->timestamps();
        });

        Schema::table('user_email_settings', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('user_email_settings');
    }
}
