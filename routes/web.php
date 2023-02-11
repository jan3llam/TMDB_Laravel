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
	Route::get('/login','UsersController@showLoginForm');
	Route::get('/signup','UsersController@showRegisterForm');
	Route::get('/user/forget-password','PasswordController@showForgetPassForm');
	Route::post('/user/forget-password','PasswordController@forgetPassword')->name('pass.forget');
});

Route::group(['as'=>'users.'],function(){
	Route::post('/login','UsersController@login')->name('login');
	Route::post('/signup','UsersController@register')->name('signup');
	Route::get('/account/verify/{token}','UsersController@verifyAccount')->name('verify');
	Route::post('/rate/{id}/{title}','UsersController@submitRating')->name('rate')->middleware('verified');
	Route::get('/user/password-reset/{token}','PasswordController@showResetPasswordForm')->name('resetForm');
	Route::post('/user/password-reset/{token}','PasswordController@resetPassword')->name('resetPass');

	//only for logged in users
	Route::group(['middleware'=>'auth:user'],function(){
		Route::get('/logout','UsersController@logout')->name('logout');
		Route::get('/user/password','PasswordController@showPasswordForm')->name('change');
		Route::post('/user/password','PasswordController@changePassword')->name('password');
		Route::get('/myratings/{page}','UsersController@showRatingsForm');
		Route::get('/myavatar','UsersController@showAvatarForm');
		Route::post('/myavatar','UsersController@changeAvatar')->name('changeAvatar');
	});	
});





