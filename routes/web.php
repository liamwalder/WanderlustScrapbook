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

// Trip

Route::get('/trips', 'TripController@index')->name('trip.index');

Route::post('/trip/create', 'TripController@store')->name('trip.store');
Route::get('/trip/create', 'TripController@create')->name('trip.create');
Route::delete('/trip/{id}', 'TripController@delete')->name('trip.delete');
Route::get('/trip/{id}', 'TripController@single')->name('trip');

// Media
Route::get('/{filename}', 'MediaController@serve');