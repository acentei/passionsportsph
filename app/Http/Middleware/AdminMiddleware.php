<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminMiddleware
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
        if (!Auth::guard($guard)->check()) 
        {
            return redirect()->guest('auth/login');
        }
        else
        {
            $user = Auth::user();   
                        
            if($user->account_type == 'Admin')
            {
                return $next($request); 
            }    
            else
                return view('errors.403');
        }
            
    }
}
