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


Route::get('/total', 'ClassController@classList')->name('total');
Route::get('/school/{school}', 'ClassController@classOfSchool')->name('school');
Route::get('/Class/{className}', 'ClassController@classOfSchool')->name('class');


Route::get('/index', function() {
    return view('/mooc/index');
})->name('index');

Route::get('/elements', function() {
    return view('/mooc/elements');
});

Route::get('/member', function() {
    return view('/mooc/member');
})->name('member');

