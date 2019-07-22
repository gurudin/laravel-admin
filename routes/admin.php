<?php
Route::prefix('admin')->group(function () {
    Route::group(['namespace' => 'Controllers', 'middleware' => ['admin']], function () {
        Route::get('welcome', function () {
            return 'Welcome';
        })->name('admin.welcome');
    });

    Route::group(['namespace' => 'Controllers'], function () {
        Route::get('login', 'LoginController@loginFrom')->name('admin.login');
        Route::post('login', 'LoginController@login')->name('admin.post.login');

        Route::get('loginOut', 'LoginController@loginOut')->name('admin.loginOut');

        Route::get('register', 'RegisterController@registerFrom')->name('admin.register');
        Route::post('register', 'RegisterController@register')->name('admin.post.register');
    });
});
