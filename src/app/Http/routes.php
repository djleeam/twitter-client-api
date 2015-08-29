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

Route::get('/', function ()
{
    //return view('welcome');
    return redirect('/api-docs');
});

Route::group(array('prefix' => 'api/v1'), function()
{
	Route::get('/users/{user_name}/recentTweets.json', 'TwitterController@getRecentTweets');

	Route::get('/users/commonFriends.json', 'TwitterController@getCommonFriends');
});
