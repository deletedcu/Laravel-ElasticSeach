<?php

namespace App\Http\Middleware;

// use Auth;
use Closure;
use App\Http\Repositories\UtilityRepository;

class Wiki
{
    public function __construct(UtilityRepository $utilsRepo)
    {
        $this->utility = $utilsRepo;
    } 
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userWikiReader = $this->utility->universalHasPermission([16]);
        $mandantWikiAllowed = $this->utility->getMandantWikiPermission();
        
        if($userWikiReader || $mandantWikiAllowed){
            return $next($request);
        } else {
            return back()->with('messageSecondary', trans('controller.noPermission'));
        }
        
        // return $next($request);
    }
}
