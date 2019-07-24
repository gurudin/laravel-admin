<?php
Route::prefix('admin')->group(function () {
    Route::group(['namespace' => 'Controllers', 'middleware' => ['admin']], function () {
        Route::get('welcome', 'SiteController@welcome')->name('admin.welcome');

        Route::get('menu', 'MenuController@index')->name('admin.menu');
        Route::get('menu/save/{id?}', 'MenuController@save')->name('admin.menu.save');

        Route::get('route', 'RouteController@index')->name('admin.route');
        Route::post('route', 'RouteController@create')->name('admin.post.route.create');
        Route::delete('route', 'RouteController@destroy')->name('admin.delete.route.destroy');

        Route::get('permission', 'PermissionController@index')->name('admin.permission');
        Route::post('permission', 'PermissionController@save')->name('admin.post.permission.save');
        Route::delete('permission', 'PermissionController@destroy')->name('admin.delete.permission.destroy');
        Route::get('permission/view/{name?}', 'PermissionController@view')->name('admin.permission.view');
        Route::post('batchPermission', 'PermissionController@batchCreateRouteChild')->name('admin.post.permission.batchRouteChild');
        Route::delete('batchPermission', 'PermissionController@batchRemoveRouteChild')->name('admin.delete.permission.batchRouteChild');

    });

    Route::group(['namespace' => 'Controllers'], function () {
        Route::get('login', 'LoginController@loginFrom')->name('admin.login');
        Route::post('login', 'LoginController@login')->name('admin.post.login');

        Route::get('logout', 'LoginController@logout')->name('admin.logout');

        Route::get('register', 'RegisterController@registerFrom')->name('admin.register');
        Route::post('register', 'RegisterController@register')->name('admin.post.register');
    });
});
