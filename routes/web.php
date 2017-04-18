<?php

use Illuminate\Http\Response;

use App\Friends;

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

Route::get('/displayStats', 'SmackTalkController@displayStats');

Route::get('/displayFriends', 'SmackTalkController@displayFriends');

// Route::get('/displayGames', 'SmackTalkController@displayGames');

Route::post('/newUser', 'SmackTalkController@newUser');