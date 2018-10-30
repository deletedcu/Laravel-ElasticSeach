 <?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserReadDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_read_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_group_id');
            $table->integer('user_id')->unsigned();  
            $table->timestamp('date_read');
            $table->timestamp('date_read_last');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
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
        Schema::drop('user_read_documents');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
