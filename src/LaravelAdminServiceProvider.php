<?php

namespace Gurudin\LaravelAdmin;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Gurudin\LaravelAdmin\Middleware\AdminAuthPermission;

class LaravelAdminServiceProvider extends ServiceProvider
{
    /**
     * Admin namespace.
     *
     * @var string
     */
    protected $namespace = 'Gurudin\LaravelAdmin';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /** Add middleware */
        Route::aliasMiddleware('admin', AdminAuthPermission::class);

        /** Add route address */
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../routes/admin.php');

        /** Add views */
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
