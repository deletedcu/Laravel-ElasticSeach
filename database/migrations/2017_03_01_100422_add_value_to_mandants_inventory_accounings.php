<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValueToMandantsInventoryAccounings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mandant_inventory_accountings', function (Blueprint $table) {
             $table->integer('value')->after('mandant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mandant_inventory_accountings', function (Blueprint $table) {
            //
        });
    }
}
