<?php

use App\Http\Router\Route;

Route::get('/', 'HomeController@index');
Route::get('/users', 'UserController@index');
Route::get('/{id}', 'HomeController@indexJson');
Route::get('/users/{id}', 'UserController@show');
Route::post('/users', 'UserController@store');
Route::put('/users/{id}', 'UserController@update');
Route::delete('/users/{id}', 'UserController@destroy');
