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
	Route::post('/rate/{id}/{title}','MenuController@submitRating')->name('rate')->middleware('verified');
	Route::get('/user/password-reset/{token}','PasswordController@showResetPasswordForm')->name('resetForm');
	Route::post('/user/password-reset/{token}','PasswordController@resetPassword')->name('resetPass');

	//google auth
	Route::group(['as'=>'google.'],function(){
		Route::get('/login/google','SocialAuthController@redirectToProvider')->name('login');
		Route::get('/auth/google/callback','SocialAuthController@handleCallback')->name('login.callback');
		Route::get('/setup/password','PasswordController@showSetupPassForm')->name('setup')->middleware('auth:user');
		Route::post('/setup/password','PasswordController@firstTimePassword')->name('password')->middleware('auth:user');
	});

	//only for logged in users
	Route::group(['middleware'=>'auth:user'],function(){
		Route::get('/logout','UsersController@logout')->name('logout');
		Route::get('/user/password','PasswordController@showPasswordForm')->name('change');
		Route::post('/user/password','PasswordController@changePassword')->name('password');
		Route::get('/myratings/{page}','MenuController@showRatingsForm');
		Route::get('/myavatar','MenuController@showAvatarForm');
		Route::post('/myavatar','MenuController@changeAvatar')->name('changeAvatar');
		Route::get('/animeQuotes/{half}','MenuController@showQuotesForm');
		Route::post('/animeQuotes/{half}','MenuController@getAnimeQuote')->name('quote');
		Route::post('/animeQuotes/{half}/favorites','MenuController@addToFav')->name('favs');
		Route::get('/animeQuotes/delete/{id}','MenuController@removeFromFav')->name('quote.remove');
	});	
});





