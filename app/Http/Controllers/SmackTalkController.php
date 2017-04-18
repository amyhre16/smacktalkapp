<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\FlippedStatus;
use App\Friends;
use App\FriendsGame;
use App\Game;
use App\People;
use App\PersonGame;

class SmackTalkController extends Controller
{
    public function displayStats(Request $request)
	{
		$stats = People :: where('id', '=', $request -> user_id)
					-> get();
					
		return response() -> json($stats);
	}

	public function displayFriends(Request $request)
	{
		$friends = Friends :: where('person_1_id', '=', $request -> user_id)
					-> orWhere('person_2_id', '=', $request -> user_id)
					-> get();

		return response() -> json($friends);
	}

	public function newUser(Request $request)
	{
		$newUser = new People();
		$newUser -> id = $request -> newUser['id'];
		$newUser -> name = $request -> newUser['name'];
		$newUser -> picture = $request -> newUser['picture']['data']['url'];

		$newUser -> save();

		$friendsList = $request -> friends_list;

		$newFriends = [];

		foreach ($friendsList as $friend) {
			array_push($newFriends, ['person_1_id' => $request -> newUser['id'], 'person_2_id' => $friend['id']]);
		}

		Friends :: insert($newFriends);
		return response() -> json($newFriends);
	}


}
