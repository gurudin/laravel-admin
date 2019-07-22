<?php
Route::prefix('admin')->group(function () {
    Route::group(['namespace' => 'Controllers', 'middleware' => ['admin']], function () {
        Route::get('welcome', function () {
            return 'aa';
        });
    });

    Route::group(['namespace' => 'Controllers'], function () {
        Route::get('login', 'LoginController@loginFrom')->name('admin.login');
    });

});
