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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// 課程頁面
Route::prefix('index')->group(function () {
    Route::get('/', 'ClassController@showIndex')->name('index');
    Route::get('/class', 'ClassController@showClass')->name('class');
    
});

// 留言板功能
Route::prefix('message')->group(function () {
    Route::post('/create', 'MessageController@create');
    Route::put('/update', 'MessageController@update');
    Route::get('/show/{classId}', 'MessageController@show');
    Route::delete('/delete', 'MessageController@delete');
});

// 留言板功能API
Route::prefix('api/message')->group(function () {
    Route::put('/update', 'ApiMessageController@update');
    Route::delete('/delete', 'ApiMessageController@delete');
});

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

// 重新抓取課程資訊
Route::get('/update/ntu', 'NTUClassController@update');
Route::get('/update/nctu', 'NCTUClassController@update');