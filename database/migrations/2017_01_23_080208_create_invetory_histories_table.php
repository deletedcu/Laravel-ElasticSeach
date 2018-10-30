<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvetoryHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_id')->unsigned();//Fk
            $table->integer('user_id')->unsigned();//Fk
            $table->integer('inventory_category_id')->nullable()->unsigned();//Fk
            $table->integer('inventory_size_id')->nullable()->unsigned();//Fk
            $table->integer('value')->nullable();
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
        Schema::drop('inventory_histories');
    }
}
