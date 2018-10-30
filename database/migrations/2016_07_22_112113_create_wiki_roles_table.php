<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWikiRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('wiki_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();//FK
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('wiki_roles');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
