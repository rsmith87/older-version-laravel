<?php

namespace App\Http\Middleware;

use Closure;
use App\Settings;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
      //print_r(Auth::id());
        if (Auth::guard($guard)->check()) {
         
            $settings = Settings::where('user_id', \Auth::id())->first();
            if(!isset($settings->firm_id) || $settings->firm_id === 0){
              return redirect()->intended('/dashboard/firm')->with('status', 'You must complete your firm information first!');
            }
            return redirect()->intended('/dashboard');
        } 

        return $next($request);
    }
}
