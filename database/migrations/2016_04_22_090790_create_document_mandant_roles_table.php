<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentMandantRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_mandant_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_mandant_id')->unsigned();
            $table->integer('role_id')->unsigned()->nullable();
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
        Schema::drop('document_mandant_roles');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
