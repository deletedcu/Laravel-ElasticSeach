<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContactMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->integer('user_id_from')->after('user_id')->nullable()->unsigned();
        });
        
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->foreign('user_id_from')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            //
        });
    }
}
