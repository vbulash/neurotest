<?php


	namespace App\Http\Support;

    use Illuminate\Support\Facades\Log;

    class CallStack
	{
        public static function back(?string $key = null, ?string $message = null)
        {
            $stack = session('stack');
            if(!$stack) return null;

            array_pop($stack);
            $context = end($stack);

            if($key) session()->flash($key, $message);

            $route = $context['route'];
            $params = $context['params'];
            $params['back'] = true;

            session()->put('stack', $stack);

            return redirect()->route($route, $params);
        }
	}
