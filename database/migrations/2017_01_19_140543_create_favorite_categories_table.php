<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoriteCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->timestamps();
        });
        
        Schema::table('favorite_categories', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
        
        Schema::table('favorite_documents', function (Blueprint $table) {
            $table->integer('favorite_categories_id')->unsigned()->nullable()->after('user_id');
        });
        
        Schema::table('favorite_documents', function (Blueprint $table) {
            $table->foreign('favorite_categories_id')
                ->references('id')
                ->on('favorite_categories')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('favorite_categories');
    }
}
