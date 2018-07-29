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
      if ($request->session()->has('locked')) {
	      if (!$request->is('dashboard/lock')) {
		      return redirect('/dashboard/lock');
	      } else {
	      	return $next($request);
	      }
      }
      return $next($request);
    }
}
