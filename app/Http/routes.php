<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'IndexController@index');
Route::get('/home', 'HomeController@index');
Route::get('/login', 'LoginController@index');
Route::get('/logout', 'LogoutController@index');
Route::get('/post', 'PostController@index');
Route::post('/post/search', 'PostController@searchPage');
Route::get('/post/{page}/{post}', 'PostController@comment');
Route::get('/history', 'HistoryController@index');
