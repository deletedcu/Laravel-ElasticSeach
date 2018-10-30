<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WikiPagesFKUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('wiki_pages', function (Blueprint $table) {
            $table->foreign('status_id')
                ->references('id')
                ->on('wiki_page_statuses')
                ->onDelete('cascade');
                
           $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        
        });
        
        Schema::table('wiki_page_histories', function (Blueprint $table) {
          
           $table->foreign('wiki_page_id')
                ->references('id')
                ->on('wiki_pages')
                ->onDelete('cascade');
                 $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
                
        });
        
        // Schema::table('wiki_roles', function (Blueprint $table) {
          
        //   $table->foreign('wiki_id','fk_wiki_roles_wiki_pages_1')
        //         ->references('id')
        //         ->on('wiki_pages')
        //         ->onDelete('cascade');
                
          
        // });
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
