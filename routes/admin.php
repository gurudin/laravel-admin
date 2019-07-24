<?php
Route::prefix('admin')->group(function () {
    Route::group(['namespace' => 'Controllers', 'middleware' => ['admin']], function () {
        Route::get('welcome', 'SiteController@welcome')->name('admin.welcome');

        Route::get('menu', 'MenuController@index')->name('admin.menu');

        Route::get('menu/save/{id?}', 'MenuController@save')->name('admin.menu.save');
    });

    Route::group(['namespace' => 'Controllers'], function () {
        Route::get('login', 'LoginController@loginFrom')->name('admin.login');
        Route::post('login', 'LoginController@login')->name('admin.post.login');

        Route::get('logout', 'LoginController@logout')->name('admin.logout');

        Route::get('register', 'RegisterController@registerFrom')->name('admin.register');
        Route::post('register', 'RegisterController@register')->name('admin.post.register');
    });
});
