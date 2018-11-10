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


Route::get('/index', function () {
    return view('mooc.index');
})->name('index');


Route::get('/index/class', 'ClassController@showClass');

Route::prefix('message')->group(function () {
    Route::post('/create', 'MessageController@create');
    Route::put('/update', 'MessageController@update');
    Route::get('/show/{classId}', 'MessageController@show');
    Route::delete('/delete', 'MessageController@delete');
});

// 重新抓取課程資訊
Route::get('/update/{school}', 'SpiderController@update');