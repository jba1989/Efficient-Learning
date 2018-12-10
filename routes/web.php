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


Route::get('/member', 'MemberController@userInfo')->middleware('login')->name('member');

// 課程頁面
Route::prefix('index')->middleware('read.redis.data')->group(function () {
    Route::get('/', 'ClassController@showIndex')->name('index');
    Route::get('/class', 'ClassController@showClass')->name('class');    
});

// 留言板功能
Route::prefix('message')->group(function () {
    Route::post('/create', 'MessageController@create')->middleware('login');
    Route::put('/update', 'MessageController@update');
    Route::get('/show/{classId}', 'MessageController@show');
    Route::delete('/delete', 'MessageController@delete');
});

// API
Route::prefix('api')->group(function () {
    // 留言板功能
    Route::prefix('/message')->group(function () {
        Route::post('/create', 'ApiMessageController@create');
        Route::put('/update', 'ApiMessageController@update');
        Route::delete('/delete', 'ApiMessageController@delete');
    });

    // 課程相關
    Route::prefix('/class')->group(function () {
        Route::get('/getOptions', 'ApiClassController@getOptions');
        Route::get('/like', 'ApiClassController@show');
        Route::put('/like', 'ApiClassController@update')->middleware('login');
    });

    // 我的最爱功能
    Route::prefix('/user')->group(function () {
        Route::get('/show', 'ApiUserController@show');
        Route::put('/update', 'ApiUserController@update')->middleware('login');
        Route::delete('/delete', 'ApiUserController@delete')->middleware('login');
    });
});

Route::get('/logout', 'Auth\LoginController@logout')->middleware('login')->name('logout');

// 重新抓取課程資訊
Route::get('/update/ntu', 'NTUClassController@update');
Route::get('/update/nctu', 'NCTUClassController@update');