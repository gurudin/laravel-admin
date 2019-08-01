<?php
namespace Gurudin\LaravelAdmin\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Gurudin\LaravelAdmin\Support\Helper;

class AdminAuthPermission
{
    private $redirectTo = 'admin.login';

    private $current;

    public function __construct()
    {
        $this->current = [
            'method' => Route::current()->methods,
            'uri'    => Route::current()->uri,
            'name'   => Route::currentRouteName(),
        ];

        if (config('admin.not_login_redirect') != '') {
            $this->redirectTo = config('admin.not_login_redirect');
        }
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
            return redirect()->route($this->redirectTo, ['source' => $this->current['uri']]);
        }

        /**
         * Is admin.
         */
        if (Helper::isAdmin()) {
            return $next($request);
        }

        /**
         * Check permissions.
         */
        if (!Helper::isPermission(
            Auth::user()->id, [
                'method' => $this->current['method'],
                'route'  => $this->current['uri']
            ])
        ) {
            if ($request->ajax()) {
                response()->json([
                    'code' => 403,
                    'msg'  => __('admin::messages.common.you-are-not-allowed-to-view-this-page')
                ], 403);
            }

            return config('admin.403_view')
                ? response()->view(config('admin.403_view'))
                : response()->json([
                    'code' => 403,
                    'msg'  => __('admin::messages.common.you-are-not-allowed-to-view-this-page')
                ], 403);
        }

        return $next($request);
    }
}
