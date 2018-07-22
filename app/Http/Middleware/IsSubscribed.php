<?php

namespace App\Http\Middleware;

use Closure;

class IsSubscribed
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
			if ($request->user() && ! $request->user()->subscribed('main')) {
				// This user is not a paying customer...
				session(['u_i' => \Auth::id()]);
				\Auth::logout();
				return redirect('register/payment');
			}

			return $next($request);
		}
}
