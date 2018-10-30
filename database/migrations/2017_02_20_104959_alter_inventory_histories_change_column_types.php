<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInventoryHistoriesChangeColumnTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_histories', function (Blueprint $table) {
            // $table->integer('user_id')->unsigned()->nullable()->change();
            $table->integer('min_stock')->default(null)->nullable()->change();
            $table->string('purchase_price')->default(null)->nullable()->change();
            $table->string('sell_price')->default(null)->nullable()->change();
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
