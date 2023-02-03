<?php

use Illuminate\Support\Facades\Route;

Route::get('/','MoviesController@index')->name('movies.index');
Route::get('/movies/{movie}','MoviesController@show')->name('movies.show');


Route::get('/actors','ActorsController@index')->name('actors.index');
Route::get('/actors/page/{page?}','ActorsController@index');
Route::get('/actors/{actor}','ActorsController@show')->name('actors.show');


Route::get('/tv','TvController@index')->name('tv.index');
Route::get('/tv/{show}','TvController@show')->name('tv.show');

Route::group(['middleware'=>'guest:user'],function(){
	Route::get('/login','UsersController@index');
	Route::get('/signup','UsersController@create');
});


Route::post('/login','UsersController@login')->name('users.login');
Route::post('/signup','UsersController@register')->name('users.signup');
Route::get('/logout','UsersController@logout');


