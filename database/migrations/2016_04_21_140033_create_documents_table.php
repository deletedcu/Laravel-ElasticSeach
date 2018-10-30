<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_type_id')->unsigned();//Fk
            $table->integer('user_id')->unsigned();//FK
            $table->integer('version');
            $table->string('name');
            $table->string('name_long')->nullable();
            $table->integer('owner_user_id')->unsigned()->nullable();//FK
            $table->integer('document_status_id')->unsigned()->default(1);//FK
            $table->string('search_tags');
            $table->text('summary');
            $table->timestamp('date_published')->nullable()->default(null);
            $table->timestamp('date_expired')->nullable();
            $table->integer('version_parent');
            $table->integer('document_group_id');
            $table->integer('iso_category_id')->unsigned()->nullable();//FK
            $table->integer('iso_category_number')->nullable();
            $table->integer('qmr_number')->nullable();
            $table->boolean('show_name');
            $table->integer('adressat_id')->unsigned()->nullable();//FK
            $table->string('betreff')->nullable();
            $table->integer('document_replaced_id');
            $table->timestamp('date_approved');
            $table->boolean('email_approval');
            $table->boolean('approval_all_roles');
            $table->boolean('pdf_upload')->nullable()->default(0);
            $table->boolean('landscape')->nullable()->default(0);
            $table->boolean('is_attachment')->nullable()->default(0);
            $table->boolean('active')->nullable()->default(1);
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
        Schema::drop('documents');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
