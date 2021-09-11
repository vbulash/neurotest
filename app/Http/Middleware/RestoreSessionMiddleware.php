<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class RestoreSessionMiddleware
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
//        Log::debug('session ID = ' . session()->getId() . ' at route = ' . Route::currentRouteName());
        if($request->has('sid')) {
            if($request->sid != session()->getId()) {
                session()->setId($request->sid);
                session()->start();
//                Log::debug('session restored');
//                Log::debug('session = ' . print_r(array_keys(session()->all()), true));
            }
        }
        return $next($request);
    }
}
