<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WikiFkUpdate22716 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wiki_roles', function (Blueprint $table) {
            $table->foreign('wiki_category_id')
                ->references('id')
                ->on('wiki_categories')
                ->onDelete('cascade');
           
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
         });
        
        Schema::table('wiki_category_users', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
           
            $table->foreign('wiki_category_id')
                ->references('id')
                ->on('wiki_categories')
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
        //
    }
}
