<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableInventoryHistoriesAddAllInventoryFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_histories', function (Blueprint $table) {
             $table->timestamp('min_stock')->after('value')->default(null)->nullable();
             $table->timestamp('purchase_price')->after('min_stock')->default(null)->nullable();
             $table->timestamp('sell_price')->after('purchase_price')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_histories', function (Blueprint $table) {
            //
        });
    }
}
