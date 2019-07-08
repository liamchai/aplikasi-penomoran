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

Route::view('/', 'login.login')->name('login');
Route::post('/login', 'UserController@login');
Route::get('/logout', 'UserController@logout');

Route::get('/{user}', 'UserController@index')->name('admin');
Route::get('/{user}/register', 'UserController@register');
Route::post('/{user}/store', 'UserController@store');

Route::get('/{user}/suratreferensi', 'LetterController@index');

Route::get('/{user}/{name}', 'UserController@show');
// Route::post('/{user}/{name}', 'UserController@update');
