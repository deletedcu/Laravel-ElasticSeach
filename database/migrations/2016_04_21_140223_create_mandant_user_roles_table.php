<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMandantUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mandant_user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mandant_user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::drop('mandant_user_roles');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
