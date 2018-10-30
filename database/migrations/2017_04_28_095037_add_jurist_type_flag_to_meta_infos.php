<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJuristTypeFlagToMetaInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jurist_category_metas', function (Blueprint $table) {
            $table->boolean('beratung')->after('name')->default(0);
            $table->boolean('active')->after('beratung')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jurist_category_metas', function (Blueprint $table) {
            //
        });
    }
}
