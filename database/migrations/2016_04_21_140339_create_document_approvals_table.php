<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();//FK
            $table->integer('document_id')->unsigned();//FK
            $table->timestamp('date_approved')->nullable()->default(null);
            $table->boolean('approved')->default(0);
            $table->timestamps();
            //$table->softDeletes();
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('document_id')
                  ->references('id')
                  ->on('documents')
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('document_approvals');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
