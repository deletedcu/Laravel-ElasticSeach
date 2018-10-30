<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWikiPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wiki_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();//FK
            $table->integer('status_id')->unsigned()->default(1);//FK
            $table->integer('category_id')->unsigned()->default(1);//FK
            $table->string('name');  
            $table->string('subject')->nullable(); 
            $table->text('content');  
            $table->boolean('allow_all')->nullable();  
            $table->timestamps();
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
        Schema::drop('wiki_pages');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
