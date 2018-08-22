<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;

class VerifyUserSession
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle($request, Closure $next)
    {
        if(empty($this->session->getId())) {
            Auth::logout();

            return redirect('/login')->withErrors(['Your user account was logged in elsewhere.']);


        } else {
            if($request->user()) {
                if ($request->user()->session_id === $this->session->getId()) {
                    return $next($request);
                } else {
                    return redirect('/login')->withErrors(['Your user account was logged in elsewhere.']);

                }
            } else {
                $response  = $next($request);
                \Auth::logout();
                return redirect()->intended('/');
            }
            return $response;
        }


    }
}
