<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['hash'])->group(function () {
	Route::resource('good', 'GoodController');
    Route::resource('group', 'GroupController');
    Route::post('/updateUser', 'UserController@update');
});

Route::get('/getUser', 'UserController@getUser');

Route::get('/home', 'HomeController@index')->name('home');
