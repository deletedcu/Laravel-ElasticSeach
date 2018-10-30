<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveFlagToMetaInfoFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jurist_category_meta_fields', function (Blueprint $table) {
            $table->boolean('active')->after('jurist_category_meta_id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jurist_category_meta_fields', function (Blueprint $table) {
            //
        });
    }
}
