<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWikiCategoryUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wiki_category_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();//FK
            $table->integer('wiki_category_id')->unsigned();//FK
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
        Schema::drop('wiki_category_users');
    }
}
