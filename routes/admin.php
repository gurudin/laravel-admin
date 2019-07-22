<?php
Route::prefix('admin')->group(function () {
    Route::group(['namespace' => 'Controllers', 'middleware' => ['admin']], function () {
        Route::get('welcome', function () {
            return 'aa';
        });
    });

    Route::get('login', 'LoginController@login')->name('admin.login');
});
