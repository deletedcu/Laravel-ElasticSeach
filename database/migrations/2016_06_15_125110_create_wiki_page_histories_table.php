<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWikiPageHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wiki_page_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wiki_page_id')->unsigned();//FK
            $table->integer('user_id')->unsigned();//FK
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
        Schema::drop('wiki_page_histories');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
