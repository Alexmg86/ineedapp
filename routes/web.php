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
    Route::get('user/loginhash', 'UserController@loginhash');
    Route::resource('access', 'AccessController');
    Route::resource('good', 'GoodController');
    Route::get('group/shop', 'GroupController@forShop');
    Route::resource('group', 'GroupController');
    Route::resource('order', 'OrderController');
    Route::resource('user', 'UserController');
    Route::resource('payment', 'PaymentController');
    Route::post('user/getstat', 'UserController@getstat');
});
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('/home', 'HomeController@index')->name('home');
