<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();


Route::get('/member', 'MemberController@userInfo')->middleware('check.login')->name('member');

// 課程頁面
Route::prefix('index')->middleware('read.redis.data')->group(function () {
    Route::get('/', 'ClassController@showIndex')->name('index');
    Route::get('/class', 'ClassController@showClass')->middleware('check.classId')->name('class');
});

// 留言板功能
Route::prefix('message')->middleware(['check.login', 'check.classId'])->group(function () {
    Route::post('/create', 'MessageController@create');
});

// API
Route::prefix('api')->group(function () {
    // 留言板功能
    Route::prefix('/message')->middleware('check.login')->group(function () {
        Route::put('/update', 'ApiMessageController@update');
        Route::delete('/delete', 'ApiMessageController@delete');
    });

    // 課程相關
    Route::prefix('/class')->group(function () {
        Route::get('/getOptions', 'ApiClassController@getOptions');
        Route::get('/like', 'ApiClassController@show')->middleware('check.classId');
        Route::put('/like', 'ApiClassController@update')->middleware(['check.login', 'check.classId']);
    });

    // 我的最爱功能
    Route::prefix('/user')->group(function () {
        Route::get('/show', 'ApiUserController@show');
        Route::put('/update', 'ApiUserController@update')->middleware(['check.login', 'check.classId']);
        Route::delete('/delete', 'ApiUserController@delete')->middleware(['check.login', 'check.classId']);
    });
});

Route::get('/logout', 'Auth\LoginController@logout')->middleware('check.login')->name('logout');