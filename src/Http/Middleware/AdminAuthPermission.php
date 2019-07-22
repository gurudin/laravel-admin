<?php
namespace Gurudin\LaravelAdmin\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminAuthPermission
{
    private $redirectTo = 'LoginController@login';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->action($this->redirectTo);
        }

        return $next($request);
    }
}
