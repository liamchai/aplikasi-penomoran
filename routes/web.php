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
Route::get('/{user}/register', 'UserController@register');
Route::post('/{user}/store', 'UserController@store');
Route::get('/{user}/editaccess/{name}', 'UserController@editAccess');
Route::patch('/{user}/editaccess/{name}', 'UserController@updateAccess');

Route::get('/{user}/daftarakses', 'AccessController@index')->name('accesslist');
Route::get('/{user}/daftarakses/create', 'UserController@create')->name('accesslist');
Route::post('/{user}/daftarakses', 'AccessController@store')->name('accesslist');
Route::get('/{user}/daftarakses/{id}', 'AccessController@show');
Route::get('/{user}/daftarakses/{id}/edit', 'AccessController@edit');
Route::patch('/{user}/daftarakses/{id}', 'AccessController@update');
Route::delete('/{user}/daftarakses/{id}', 'AccessController@destroy');

Route::get('/{user}/daftarsurat', 'LetterController@index');
Route::get('/{user}/daftarsurat/create', 'LetterController@create');
Route::post('/{user}/daftarsurat', 'LetterController@store');
Route::get('/{user}/daftarsurat/{id}', 'LetterController@show');
Route::get('/{user}/daftarsurat/{id}/edit', 'LetterController@edit');
Route::patch('/{user}/daftarsurat/{id}', 'LetterController@update');
Route::delete('/{user}/daftarsurat/{id}', 'LetterController@destroy');

Route::get('/{user}/surat/{namasurat}', 'LetterController@list');
// Route::post('/{user}/surat/{namasurat}', 'LetterController@store');

Route::get('/{user}/{name}', 'UserController@edit');
Route::patch('/{user}/{name}', 'UserController@update');
Route::delete('/{user}/{name}', 'UserController@destroy');
