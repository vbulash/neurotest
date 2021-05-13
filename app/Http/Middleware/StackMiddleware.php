<?php

namespace App\Http\Middleware;

use App\Models\CallRoute;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class StackMiddleware
{
    public function allowed(array $keys)
    {
        $route = Route::currentRouteName();
        foreach ($keys as $key) {
            if (str_ends_with($route, $key)) return true;
        }
        return false;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $route = Route::currentRouteName();
        if ($this->allowed(['.index', '.create', '.edit', '.destroy']) &&
            !$this->allowed(['.index.data', '.store', '.update', '.show', '.back', '.copy']))
            if (!$request->has('back')) {
                $context = [
                    'route' => $route,
                    'params' => Route::current()->parameters()
                ];
                $stack = session('stack');
                if($stack) {
                    $last = end($stack);

                    if (($last['route'] != $context['route']) ||
                        ($last['params'] != $context['params'])) {
                        array_push($stack, $context);
                    }
                } else {
                    $stack = [];
                    array_push($stack, $context);
                }
                session()->put('stack', $stack);
            }
        return $next($request);
    }
}
