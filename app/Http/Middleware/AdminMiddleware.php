<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->has('sid')) {
            if(session()->getId() != $request->sid) {
                session()->setId($request->sid);
                session()->start();
            }
        }
        if(Auth::check()) {
            return $next($request);
        } else {
            // Вернуться на сессию логина
            return redirect()->route('login.create', ['sid' => session()->getId()]);
        }
    }
}
