<?php

namespace App\Http\Middleware;

use Closure;
use App\Timer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


class TimerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
      
       
      return $next($request);
    }
}
