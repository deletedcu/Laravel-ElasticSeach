<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentMandantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_mandants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->unsigned(); //FK
            $table->integer('editor_variant_id')->unsigned();//FK 
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
        Schema::drop('document_mandants');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
