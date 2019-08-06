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

Route::view('/', 'login.login')->name('login')->middleware('guest');
Route::post('/login', 'UserController@login')->name('logged');
Route::get('/logout', 'UserController@logout')->name('logout');

Route::get('/{user}', 'UserController@index');
Route::get('/{user}/daftaruser', 'UserController@list')->name('admin');
Route::get('/{user}/daftaruser/create', 'UserController@create');
Route::post('/{user}/daftaruser', 'UserController@store');
Route::get('/{user}/daftaruser/{name}', 'UserController@show');
Route::get('/{user}/daftaruser/{name}/edit', 'UserController@edit');
Route::patch('/{user}/daftaruser/{name}', 'UserController@update');
Route::delete('/{user}/daftaruser/{name}', 'UserController@destroy');
Route::get('/{user}/daftaruser/{name}/editaccess', 'UserController@editAccess');
Route::patch('/{user}/daftaruser/{name}/editaccess', 'UserController@updateAccess');

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
Route::get('/{user}/infosurat/{namasurat}', 'LetterController@getInfo');
