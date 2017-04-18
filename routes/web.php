<?php

use Illuminate\Http\Response;

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

Route::get('/', function () {
	// $stats = App\People::where('id', '=', '10212631339123286')
	// 				-> get();


	$stats = App\Friends :: where('person_1_id', '=', '10212631339123286')
			-> orWhere('person_2_id', '=', '10212631339123286')
			-> get();
    // return view('welcome', compact('stats'));
	return response() -> json($stats);
});


Route::get('/displayStats', 'SmackTalkController@displayStats');

Route::get('/displayFriends', 'SmackTalkController@displayFriends');