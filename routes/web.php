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

Route::prefix('index')->group(function () {
    Route::get('/', 'ClassController@showIndex')->name('index');
    Route::get('/class', 'ClassController@showClass')->name('class');
    
});

Route::prefix('message')->group(function () {
    Route::post('/create', 'MessageController@create');
    Route::put('/update', 'MessageController@update');
    Route::get('/show/{classId}', 'MessageController@show');
    Route::delete('/delete', 'MessageController@delete');
});

Route::prefix('api')->group(function () {
    Route::get('/class', 'ApiClassController@showClass');
    Route::get('/message', 'ApiClassController@showClass');
});


Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

// 重新抓取課程資訊
Route::get('/update/ntu', 'NTUClassController@update');
Route::get('/update/nctu', 'NCTUClassController@update');