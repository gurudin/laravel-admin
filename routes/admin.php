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

        Route::get('role', 'RoleController@index')->name('admin.role');
        Route::post('role', 'RoleController@create')->name('admin.post.role.create');
        Route::put('role', 'RoleController@update')->name('admin.put.role.update');
        Route::delete('role', 'RoleController@destroy')->name('admin.delete.role.destroy');
        Route::get('role/view/{name?}', 'RoleController@view')->name('admin.role.view');

        Route::get('assignment', 'AssignmentController@index')->name('admin.assignment');
        Route::delete('assignment', 'AssignmentController@destroy')->name('admin.delete.assignment.destroy');
        Route::get('assignment/edit/{id?}', 'AssignmentController@editView')->name('admin.assignment.update');
        Route::get('assignment/view/{id?}', 'AssignmentController@view')->name('admin.assignment.view');
        Route::post('batchAssignment', 'AssignmentController@batchCreateAssignment')->name('admin.post.assignment.batchAssignment');
        Route::delete('batchAssignment', 'AssignmentController@batchRemoveAssignment')->name('admin.delete.assignment.batchAssignment');
    });

    Route::group(['namespace' => 'Controllers'], function () {
        Route::get('login', 'LoginController@loginFrom')->name('admin.login');
        Route::post('login', 'LoginController@login')->name('admin.post.login');

        Route::get('logout', 'LoginController@logout')->name('admin.logout');

        Route::get('register', 'RegisterController@registerFrom')->name('admin.register');
        Route::post('register', 'RegisterController@register')->name('admin.post.register');
    });
});
