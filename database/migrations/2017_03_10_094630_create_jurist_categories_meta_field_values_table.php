<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuristCategoriesMetaFieldValuesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jurist_categories_meta_field_values', function (Blueprint $table) {
            $table->increments('id');
            $table->text('value');
            $table->integer('jurist_category_meta_field_id')->unsigned(); //Fk jurist_category_meta_fields
            $table->integer('document_id')->unsigned(); //Fk
            $table->timestamps();
        });

        Schema::table('jurist_categories_meta_field_values', function (Blueprint $table) {
            $table->foreign('jurist_category_meta_field_id', 'jcmf_jcmfi_fk')
                ->references('id')
                ->on('jurist_category_meta_fields')
                ->onUpdate('cascade');
        });
        Schema::table('jurist_categories_meta_field_values', function (Blueprint $table) {
            $table->foreign('document_id', 'jcmfi_document_fk')
                ->references('id')
                ->on('documents')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('jurist_categories_meta_field_values');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
