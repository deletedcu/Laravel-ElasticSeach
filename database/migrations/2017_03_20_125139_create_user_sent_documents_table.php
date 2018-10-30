<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSentDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_sent_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_email_setting_id')->unsigned();
            $table->integer('document_id')->unsigned();
            $table->boolean('sent')->default(0);
            $table->timestamps();
        });
        
        Schema::table('user_sent_documents', function (Blueprint $table) {
            $table->foreign('user_email_setting_id')
                ->references('id')
                ->on('user_email_settings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_sent_documents');
    }
}
