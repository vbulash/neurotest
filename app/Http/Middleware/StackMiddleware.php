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
            !$this->allowed(['.index.data', '.store', '.update', '.show', '.back'])) if (!$request->has('back')) {
            $context = CallRoute::create([
                'route' => $route,
                'params' => serialize(Route::current()->parameters()),
                //'breadcrumb' => $breadcrumb ?: null,
                'user_id' => Auth::id()
            ]);
            $context->save();
        }
        return $next($request);
    }
}
