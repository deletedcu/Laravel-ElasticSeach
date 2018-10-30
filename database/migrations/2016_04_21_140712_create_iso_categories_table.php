<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIsoCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iso_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('iso_category_parent_id');
            $table->string('name');
            $table->string('slug');
            $table->boolean('active');
            $table->boolean('parent');
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
        Schema::drop('iso_categories');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
