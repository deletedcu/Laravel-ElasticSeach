<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        // Dynamically register permissions with Laravel's Gate.
     /*   foreach ($this->getRoles() as $role) {
            $gate->define($role->name, function ($user) use ($role) {
                return $user->hasRole($role);
            });
        }*/
    }
    
    protected function getRoles()
    {
       return Role::where('id','>',0)->get(); 
    }
}
