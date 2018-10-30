<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMandantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mandants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');  
            $table->string('kurzname');  
            $table->string('mandant_number',10);  
            $table->boolean('rights_wiki');  
            $table->boolean('rights_admin');  
            $table->boolean('active');  
            $table->string('logo');  
            $table->integer('mandant_id_hauptstelle');  
            $table->boolean('hauptstelle');  
            $table->string('adresszusatz');  
            $table->string('strasse',100);  
            $table->string('hausnummer',10);
            $table->string('plz',10);
            $table->string('ort',100);
            $table->string('bundesland',100);
            $table->string('telefon',30);  
            $table->string('kurzwahl',30);  
            $table->string('fax',30);  
            $table->string('email',30);  
            $table->string('website',50);  
            // $table->integer('geschaftsfuhrer_id');
            $table->text('geschaftsfuhrer_history');
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
        Schema::drop('mandants');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
