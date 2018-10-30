<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuristCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurist_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jurist_category_parent_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('slug');
            $table->boolean('active');
            $table->boolean('parent');
            $table->timestamps();
        });
        
         Schema::table('documents', function (Blueprint $table) {
           $table->foreign('jurist_category_id')
                ->references('id')
                ->on('jurist_categories')
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
        Schema::drop('jurist_categories');
    }
}
