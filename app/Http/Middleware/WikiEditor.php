<?php

namespace App\Http\Middleware;

// use Auth;
use Closure;
use App\Http\Repositories\UtilityRepository;

class WikiEditor
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
         return $next($request);
        /*$userWikiEditor = $this->utility->universalHasPermission([15]);

        if($userWikiEditor){
            return $next($request);
        } else {
            return back()->with('messageSecondary', trans('controller.noPermission'));
        }*/
        
        // return $next($request);
    }
}
