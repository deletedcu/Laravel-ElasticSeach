    <?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();//FK
            $table->integer('document_id')->unsigned();//FL
            $table->boolean('freigeber')->nullable()->default(0);
            $table->string('betreff');
            $table->text('comment');
            $table->boolean('active')->nullable()->default(0);
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
        Schema::drop('document_comments');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
