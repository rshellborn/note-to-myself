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


Route::get('/', 'HomeController@index');
Route::post('/', function () {
    return view('wrongType');
});

Route::get('/home', 'HomeController@index');
Route::post('/home', 'FormController@store');

Route::get('/unlock', 'UnlockController@index');
Route::post('/unlock', 'UnlockController@unlock');

Route::get('/activate', 'ActivateController@index');

Auth::routes();

