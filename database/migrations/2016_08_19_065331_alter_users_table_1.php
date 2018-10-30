<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->timestamp('last_login')->nullable()->change(); // NOT WORKS
            $table->timestamp('last_login_history')->default('1999-01-01 00:00:00')->nullable();
            DB::statement('ALTER TABLE `users` MODIFY `last_login` TIMESTAMP NULL;');
            // DB::statement('ALTER TABLE `users` MODIFY `last_login_history` TIMESTAMP NULL DEFAULT "1999-01-01 00:00:00";');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
