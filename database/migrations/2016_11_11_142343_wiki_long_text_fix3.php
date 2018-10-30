<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WikiLongTextFix3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        DB::statement('ALTER TABLE wiki_pages MODIFY COLUMN content LONGTEXT');
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wiki_pages', function (Blueprint $table) {
            //
        });
    }
}
