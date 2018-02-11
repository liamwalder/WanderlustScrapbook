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

Auth::routes();


Route::group(['middleware' => ['auth']], function () {

    Route::get('/', function() {
       return redirect('/trips');
    });

    Route::get('/trips', 'TripController@index')->name('trip.index');
    Route::post('/trip/create', 'TripController@store')->name('trip.store');
    Route::get('/trip/create', 'TripController@create')->name('trip.create');

    Route::group(['middleware' => ['trip.user']], function() {
        Route::delete('/trip/{id}', 'TripController@delete')->name('trip.delete');
    });

});

Route::get('/media/view/{filename}', 'MediaController@serve')->name('media.single');
Route::get('/trip/{hash}', 'TripController@single')->name('trip');

