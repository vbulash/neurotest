<?php


	namespace App\Http\Support;

    use App\Models\CallRoute;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Route;

    class CallStack
	{
        public static function back(?string $key = null, ?string $message = null)
        {
            $last = CallRoute::all()->where('user_id', Auth::id())->last();
            $last->delete();

            $context = CallRoute::all()->where('user_id', Auth::id())->last();
            if(!$context) return null;

            if($key) session()->flash($key, $message);

            $route = $context->route;
            $params = unserialize($context->params);
            $params['back'] = true;

            return redirect()->route($route, $params);
        }
	}
