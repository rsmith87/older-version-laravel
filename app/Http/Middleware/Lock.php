<?php

namespace App\Http\Middleware;

use Closure;

class Lock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	//print_r($request->session()->get('locked'));
			//print_r($request->path());
			//exit;
      if (\Session::get('locked') === true && $request->path() != 'dashboard/lock') {
		    return redirect()->intended('/dashboard/lock');
      }
      return $next($request);

    }
}
