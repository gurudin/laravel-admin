<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Super administrator account.
    | 超级管理员账号
    |--------------------------------------------------------------------------
    |
    | Account with super permissions.
    |
    */
    'admin_email' => [
        'admin@admin.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed route.
    | 允许放行路由
    |--------------------------------------------------------------------------
    |
    | Routes that do not require permission detection.
    | 不需要检测权限的路由
    |
    | example:
    |  
    | 'allow' => [
    |   ['method' => 'get', 'uri' => '/admin/welcome'],
    |   ['method' => 'post', 'uri' => '/admin/welcome'],
    |   ['method' => 'any', 'uri' => '/admin/welcome'],
    | ],
    */
    'allow' => [],

    /*
    |--------------------------------------------------------------------------
    | Custom blade
    | 自定义模板
    |--------------------------------------------------------------------------
    |
    | Extends parent class template.
    |
    | Menu item: Gurudin\Admin\Support\Helper::authMenu(Auth::user(), request()->group);
    |
    | Blade example:
    | <head>
    |   <title>@yield('title') {{ config('app.name', '') }}</title>
    |   @yield('style')
    | </head>
    |
    | <body>
    |
    |   ...
    |   ...
    |
    | @yield('script')
    | </body>
    |
    */
    'extends_blade' => 'admin::layouts.app',

    /*
    |--------------------------------------------------------------------------
    | Welcome page loading view.
    | 欢迎页加载视图
    |--------------------------------------------------------------------------
    |
    | Welcome page loading view.
    |
    */
    'welcome_view' => 'admin::welcome',
];
