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
Route::post('/login', 'UserController@login')->name('logged');
Route::get('/logout', 'UserController@logout')->name('logout');

Route::get('/{user}', 'UserController@index');
Route::get('/{user}/daftaruser', 'UserController@show')->name('admin');
Route::get('/{user}/accesslist', 'UserController@accesslist')->name('accesslist');
Route::get('/{user}/accesslist/register', 'UserController@accesslistregister')->name('accesslist');
Route::post('/{user}/accesslist', 'UserController@accessliststore')->name('accesslist');

Route::get('/{user}/register', 'UserController@register');
Route::post('/{user}/store', 'UserController@store');

Route::get('/{user}/surat/listsurat', 'LetterController@index');
Route::get('/{user}/surat/{namasurat}', 'LetterController@list');
Route::post('/{user}/surat/{namasurat}', 'LetterController@store');
// Route::get('/{user}/surat peringatan', 'LetterController@index');

Route::get('/{user}/{name}', 'UserController@edit');
Route::patch('/{user}/{name}', 'UserController@update');
Route::delete('/{user}/{name}', 'UserController@destroy');
