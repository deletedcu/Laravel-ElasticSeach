<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForeignKeysUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            
            $table->foreign('document_type_id','fk_documents-document_type_id1')
                ->references('id')
                ->on('document_types')
                ->onDelete('cascade');
                
            $table->foreign('document_status_id')
                ->references('id')
                ->on('document_statuses')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('iso_category_id')
                ->references('id')
                ->on('iso_categories')
                ->onDelete('cascade');

            $table->foreign('adressat_id')
                ->references('id')
                ->on('adressats')
                ->onDelete('cascade');

        });

        Schema::table('mandant_users', function (Blueprint $table) {
            $table->foreign('mandant_id')
                ->references('id')
                ->on('mandants')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('mandant_user_roles', function (Blueprint $table) {
            $table->foreign('mandant_user_id')
                ->references('id')
                ->on('mandant_users')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
        });


        Schema::table('document_comments', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade');
        });


        Schema::table('document_mandants', function (Blueprint $table) {
            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade');

            $table->foreign('editor_variant_id')
                ->references('id')
                ->on('editor_variants')
                ->onDelete('cascade');
        });
        
        Schema::table('document_mandant_mandants', function (Blueprint $table) {
            $table->foreign('document_mandant_id')
                ->references('id')
                ->on('document_mandants')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        
        Schema::table('document_mandant_roles', function (Blueprint $table) {
            $table->foreign('document_mandant_id')
                ->references('id')
                ->on('document_mandants')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('editor_variants', function (Blueprint $table) {
            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade');
        });

        Schema::table('editor_variant_documents', function (Blueprint $table) {
            $table->foreign('editor_variant_id')
                ->references('id')
                ->on('editor_variants')
                ->onDelete('cascade');

            $table->foreign('document_status_id')
                ->references('id')
                ->on('document_statuses')
                ->onDelete('cascade');

            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade');
        });

        Schema::table('mandant_infos', function (Blueprint $table) {
            $table->foreign('mandant_id', 'fk_mandant_infos_mandant_id')
                ->references('id')
                ->on('mandants')
                ->onDelete('cascade');
        });

        Schema::table('internal_mandant_users', function (Blueprint $table) {
            $table->foreign('mandant_id')
                ->references('id')
                ->on('mandants')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('document_coauthors', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade');
        });

        Schema::table('document_uploads', function (Blueprint $table) {
            $table->foreign('editor_variant_id', 'fk_document_uploads_editor_variant_id')
                ->references('id')
                ->on('editor_variants')
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
        //
    }
}
