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
Route::get('/classList', 'ClassController@classList')->name('classList');
Route::get('/school/{school}', 'ClassController@classOfSchool')->name('school');
Route::get('/class/{className}', 'ClassController@singleClass')->name('class');

Route::prefix('message')->group(function () {
    Route::post('/store', 'MessageController@store');
    Route::post('/edit', 'MessageController@edit');
    Route::post('/show', 'MessageController@show');
    Route::post('/delete', 'MessageController@delete');
});

// 重新抓取課程資訊
Route::get('/update', 'SpiderController@update');


Route::get('ajax',function(){
    return view('message');
 });
 Route::get('/getmsg','AjaxController@index');
