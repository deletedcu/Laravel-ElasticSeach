<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditorVariantDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editor_variant_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('editor_variant_id')->unsigned();
            $table->integer('document_status_id')->unsigned();
            $table->integer('document_group_id');
            $table->integer('document_id')->unsigned();
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
        Schema::drop('editor_variant_documents');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
