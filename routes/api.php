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

Route::group(['middleware' => ['api']], function () {

    Route::post('register', 'AuthController@register')->name('api.register');
    Route::post('login', 'AuthController@login')->name('api.login');
});


Route::group(['middleware' => ['api','auth']], function () {
    Route::post('/tweet/create', 'TweetController@store')->name('tweet.create');
    Route::post('/tweet/delete/{id}', 'TweetController@delete')->name('tweet.delete');
    Route::get('/timeline', 'TweetController@show')->name('tweet.show');
    Route::post('/user/follow/{id}', 'UserController@follow')->name('user.follow');
    Route::post('/user/unfollow/{id}', 'UserController@unfollow')->name('user.unfollow');
});