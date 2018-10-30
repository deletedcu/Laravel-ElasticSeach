<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserSentDocumentsTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        
        Schema::table('user_sent_documents', function (Blueprint $table) {
            
            $table->dropForeign(['user_email_setting_id']);
            
            $table->foreign('user_email_setting_id')
                ->references('id')
                ->on('user_email_settings')
                ->onDelete('cascade');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_sent_documents', function (Blueprint $table) {
            //
        });
    }
}
