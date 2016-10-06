<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * EXAMPLE
 *
 *
   Route::get('/', [ -------> That will be the URl you want posted in the URl bar
    'uses' => '',   --------> This tells what controller to look at
    'as'   => '',   --------> name of the route ( EX: in a form action="{{ route->('') }}"
    'middleware' => ['']    >This will take the'guset from Kernel.php and redirect to home if a logged in user tries to go to signin.php
    ]);
 *
 *
 */


/**
 * Home
 */
Route::get('/', [
    'uses' => '\App\Http\Controllers\HomeController@index',
    'as'   => 'home',
]);


/****************
 * Authentication
 ***************/

/**
 * SignUp
 */
Route::get('/signup', [
    'uses' => '\App\Http\Controllers\AuthController@getSignup',
    'as'   => 'auth.signup',
    'middleware' => ['guest']
]);

Route::post('/signup', [
    'uses' => '\App\Http\Controllers\AuthController@postSignup',
    'middleware' => ['guest']
]);

/**
 * SignIn
 */
Route::get('/signin', [
    'uses' => '\App\Http\Controllers\AuthController@getSignin',
    'as'   => 'auth.signin',
    'middleware' => ['guest']
]);

Route::post('/signin', [
    'uses' => '\App\Http\Controllers\AuthController@postSignin',
    'middleware' => ['guest']
]);

/**
 * Signout Route
 */
Route::get('/signout', [
    'uses' => '\App\Http\Controllers\AuthController@getSignout',
    'as'   => 'auth.signout',
]);

/**
 * Search
 */
Route::get('/search', [
    'uses' => '\App\Http\Controllers\SearchController@getResults',
    'as'   => 'search.results',
]);


/**
 * User Profile
 */
Route::get('/user/{username}', [
    'uses' => '\App\Http\Controllers\ProfileController@getProfile',
    'as'   => 'profile.index',
]);

Route::get('/profile/edit', [
    'uses' => '\App\Http\Controllers\ProfileController@getEdit',
    'as'   => 'profile.edit',
    'middleware' => ['auth']
]);

Route::post('/profile/edit', [
    'uses' => '\App\Http\Controllers\ProfileController@postEdit',
    'middleware' => ['auth']
]);


/**
 * Friends Route
 */
Route::get('/friends', [
    'uses' => '\App\Http\Controllers\FriendController@getIndex',
    'as'   => 'friend.index',
    'middleware' => ['auth']
]);


/**
 * Add Friend
 */
Route::get('/friends/add/{username}', [
    'uses' => '\App\Http\Controllers\FriendController@getAdd',
    'as'   => 'friend.add',
    'middleware' => ['auth']
]);


/**
 * Accept Friend Request
 */
Route::get('/friends/accept/{username}', [
    'uses' => '\App\Http\Controllers\FriendController@getAccept',
    'as'   => 'friend.accept',
    'middleware' => ['auth']
]);


/**
 * Delete Friend
 */
Route::post('/friends/delete/{username}', [
    'uses' => '\App\Http\Controllers\FriendController@postDelete',
    'as'   => 'friend.delete',
    'middleware' => ['auth']
]);


/**
 * Statuses Route
 * Post route because we are posting data
 */
Route::post('/status', [
    'uses' => '\App\Http\Controllers\StatusController@postStatus',
    'as'   => 'status.post',
    'middleware' => ['auth']
]);

/**
 * Statuses Reply Route
 */
Route::post('/status/{statusId}/reply', [
    'uses' => '\App\Http\Controllers\StatusController@postReply',
    'as'   => 'status.reply',
    'middleware' => ['auth']
]);

/**
 * Status Like route
 */
Route::get('/status/{statusId}/like', [
    'uses' => '\App\Http\Controllers\StatusController@getLike',
    'as'   => 'status.like',
    'middleware' => ['auth']
]);