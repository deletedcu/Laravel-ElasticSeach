<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuristCategoryMetaFieldsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jurist_category_meta_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('jurist_category_meta_id')->unsigned(); //Fk
            $table->timestamps();
        });

        Schema::table('jurist_category_meta_fields', function (Blueprint $table) {
            $table->foreign('jurist_category_meta_id')
                ->references('id')
                ->on('jurist_category_metas')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('jurist_category_meta_fields');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
