<?php

use Illuminate\Support\Facades\Route;


Route::group(['as'=>'movies.'],function(){
	Route::get('/','MoviesController@index')->name('index');
	Route::get('/movies/{movie}','MoviesController@show')->name('show');
});


Route::group(['as'=>'actors.','prefix' => 'actors'],function(){
	Route::get('/','ActorsController@index')->name('index');
	Route::get('/page/{page?}','ActorsController@index');
	Route::get('/{actor}','ActorsController@show')->name('show');
});


Route::group(['as'=>'tv.','prefix' => 'tv'],function(){
	Route::get('/','TvController@index')->name('index');
	Route::get('/{show}','TvController@show')->name('show');
});


Route::group(['middleware'=>'guest:user'],function(){
	Route::get('/login','UsersController@index');
	Route::get('/signup','UsersController@create');
});

Route::group(['as'=>'users.'],function(){
	Route::post('/login','UsersController@login')->name('login');
	Route::post('/signup','UsersController@register')->name('signup');
	Route::get('/account/verify/{token}', 'UsersController@verifyAccount')->name('verify');
	Route::get('/logout','UsersController@logout')->name('logout');
	Route::post('/movieRate/{movie}','UsersController@submitRating')->name('rate')->middleware('verified');
});





