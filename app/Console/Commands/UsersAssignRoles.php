<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\MandantUser;
use App\MandantUserRole;

class UsersAssignRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:assign-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigns specified user roles.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * ROLES LIST:
         * 14 Historien Leser
         * 40 Intranet Benutzer 
         **/

        // Every non-NEPTUN user ($mandant->rights_admin == false) should get "Intranet User" role
        $mandantUsersRoles = MandantUserRole::where('role_id', 40)->groupBy('mandant_user_id')->get();
        $mandantUsers = MandantUser::whereNotIn('id', array_pluck($mandantUsersRoles, 'mandant_user_id'))->orderBy('mandant_id')->orderBy('user_id')->get();
        foreach($mandantUsers as $mu){
            if($mu->mandant->rights_admin == false)
                MandantUserRole::create(['mandant_user_id' => $mu->id, 'role_id' => 40]);
        }
        
        // ALL users should get "Historien Leser" role
        $mandantUsersRoles = MandantUserRole::where('role_id', 14)->groupBy('mandant_user_id')->get();
        $mandantUsers = MandantUser::whereNotIn('id', array_pluck($mandantUsersRoles, 'mandant_user_id'))->orderBy('mandant_id')->orderBy('user_id')->get();
        foreach($mandantUsers as $mu){
            MandantUserRole::create(['mandant_user_id' => $mu->id, 'role_id' => 14]);
        }
        
    }
}
