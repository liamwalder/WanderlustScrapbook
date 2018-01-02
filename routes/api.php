<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/locations', 'Api\LocationsController@index');
Route::post('/locations', 'Api\LocationsController@store');

Route::post('/trip/{id}/location', 'Api\LocationController@store');
Route::put('/location/{id}', 'Api\LocationController@update');

Route::get('/trip/{id}', 'Api\TripController@single');
Route::put('/trip/{id}', 'Api\TripController@update');

Route::post('/media', 'Api\MediaController@store');
Route::delete('/files/{id}', 'Api\MediaController@delete');

Route::post('/entry', 'Api\EntryController@store');
Route::put('/entry/{id}', 'Api\EntryController@update');