<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeJuristFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('jurist_files', 'mandant_id') == false){
            Schema::table('jurist_files', function (Blueprint $table) {
                $table->dropForeign('jurist_files_mandant_id_foreign');
                $table->dropColumn('mandant_id');
            });
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jurist_files', function (Blueprint $table) {
            //
        });
    }
}
