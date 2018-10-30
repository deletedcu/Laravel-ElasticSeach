<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJuristInfoToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
             $table->integer('jurist_category_meta_id')->unsigned()->nullable(); //Fk
             $table->text('jurist_log_text')->nullable(); //Fk
        });
        
        
        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('jurist_category_meta_id')
                ->references('id')
                ->on('jurist_category_metas')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            //
        });
    }
}
