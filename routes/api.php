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

Route::get('/trip/{hash}', 'Api\TripController@single');

Route::group(['middleware' => ['auth:api']], function () {
    Route::put('/trip/{id}', 'Api\TripController@update');
    Route::post('/trip/{id}/location', 'Api\LocationController@store');

    Route::get('/locations', 'Api\LocationsController@index');
    Route::post('/locations', 'Api\LocationsController@store');
    Route::put('/location/{id}', 'Api\LocationController@update');
    Route::delete('/location/{id}', 'Api\LocationController@delete');

    Route::post('/media/location/attach', 'Api\MediaController@attachToLocation');

    Route::post('/media', 'Api\MediaController@store');

    Route::put('/media/{id}', 'Api\MediaController@update');
    Route::delete('/files/uuid/{UUID}', 'Api\MediaController@deleteByUUID');
    Route::delete('/files/{id}', 'Api\MediaController@delete');

    Route::post('/entry', 'Api\EntryController@store');
    Route::put('/entry/{id}', 'Api\EntryController@update');
    Route::delete('/entry/{id}', 'Api\EntryController@delete');
});