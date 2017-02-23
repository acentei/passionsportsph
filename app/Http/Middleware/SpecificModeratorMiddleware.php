<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class SpecificModeratorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::user())
        {
            $user = Auth::user();   
                        
            if($user->account_type == 'Moderator')
            {                
                if($user->handled_league != 0)
                {
                    if(Session::get("selectedLeague") == $user->handled_league )
                    {
                        return $next($request); 
                    }
                    else
                        return view('errors.403'); 
                }
                else
                    return $next($request); 
            }    
            else
            {
                if($user->account_type == 'Admin')
                {
                    return $next($request); 
                }
                else
                    return view('errors.403');
            }
                
        }
        else
            return $next($request); 
    }
}
