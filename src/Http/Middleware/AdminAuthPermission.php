<?php
namespace Gurudin\LaravelAdmin\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminAuthPermission
{
    private $redirectTo = '\Gurudin\LaravelAdmin\Controllers\LoginController@loginFrom';

    private $current;

    public function __construct()
    {
        $this->current = [
            'method' => Route::current()->methods,
            'uri'    => Route::current()->uri,
            'name'   => Route::currentRouteName(),
        ];
    }

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
            return redirect()->action($this->redirectTo, ['source' => $this->current['uri']]);
        }

        return $next($request);
    }
}
