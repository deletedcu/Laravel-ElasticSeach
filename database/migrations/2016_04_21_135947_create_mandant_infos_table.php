<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMandantInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mandant_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mandant_id')->unsigned();
            $table->string('prokura');  
            $table->string('betriebsnummmer',30);  
            $table->string('handelsregister',30);
            $table->text('handelsregister_sitz');  
            $table->text('steuernummer', 30);  
            $table->text('steuernummer_lohn', 30);  
            $table->text('ust_ident_number', 30);  
            $table->text('zausatzinfo_steuer');  
            $table->string('berufsgenossenschaft_number', 30);  
            $table->text('berufsgenossenschaft_zusatzinfo');  
            // $table->timestamp('erlaubniss_gultig_ab')->nullable();
            $table->string('erlaubniss_gultig_ab', 50);
            $table->string('erlaubniss_gultig_von');  
            $table->string('geschaftsjahr',30);
            $table->text('geschaftsjahr_info');  
            $table->text('bankverbindungen');   
            $table->text('info_wichtiges');  
            $table->text('info_sonstiges');
            $table->text('gewerbeanmeldung_history');
            $table->boolean('unbefristet');
            // $table->timestamp('befristet_bis')->nullable();
            $table->string('befristet_bis', 50);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('mandant_id')
                  ->references('id')
                  ->on('mandants')
                  ->onDelete('cascade');
       
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
        Schema::drop('mandant_infos');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
