<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InventoryRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Inventries table
        Schema::table('inventories', function (Blueprint $table) {
            $table->foreign('inventory_category_id')
                ->references('id')
                ->on('inventory_categories');
        });
        
        Schema::table('inventories', function (Blueprint $table) {
            $table->foreign('inventory_size_id')
                ->references('id')
                ->on('inventory_sizes');
        });
        
        //Inventory histories
        Schema::table('inventory_histories', function (Blueprint $table) {
            $table->foreign('inventory_id')
                ->references('id')
                ->on('inventories');
        });
        
        Schema::table('inventory_histories', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
        
        Schema::table('inventory_histories', function (Blueprint $table) {
            $table->foreign('inventory_category_id')
                ->references('id')
                ->on('inventory_categories');
        });
        
        Schema::table('inventory_histories', function (Blueprint $table) {
            $table->foreign('inventory_size_id')
                ->references('id')
                ->on('inventory_sizes');
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
