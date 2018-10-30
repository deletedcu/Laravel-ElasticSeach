<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMandantInventoryAccountingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mandant_inventory_accountings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_id')->unsigned();//Fk
            $table->integer('mandant_id')->unsigned();//Fk
            $table->integer('inventory_category_id')->nullable()->unsigned();//Fk
            $table->integer('inventory_size_id')->nullable()->unsigned();//Fk
            $table->string('sell_price');
            $table->boolean('accounted_for')->default(0)->nullable();
            $table->timestamps();
        });
        
        Schema::table('mandant_inventory_accountings', function (Blueprint $table) {
            $table->foreign('inventory_id')
                ->references('id')
                ->on('inventories');
        });
        
        Schema::table('mandant_inventory_accountings', function (Blueprint $table) {
            $table->foreign('mandant_id')
                ->references('id')
                ->on('mandants');
        });
        
        Schema::table('mandant_inventory_accountings', function (Blueprint $table) {
            $table->foreign('inventory_category_id')
                ->references('id')
                ->on('inventory_categories');
        });
        
        Schema::table('mandant_inventory_accountings', function (Blueprint $table) {
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
        Schema::drop('mandant_inventory_accountings');
    }
}
