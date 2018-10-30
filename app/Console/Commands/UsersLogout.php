<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class UsersLogout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:logout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Logout users.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::whereNotNull('remember_token')->get();
        foreach( $users as $user){
            $user->remember_token = null;
            $user->save();
        }
    }
}
