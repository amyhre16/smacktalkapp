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
    public function displayStats()
	{
		$stats = People :: where('id', '=', '10212631339123286')
					-> get();
		return response() -> json($stats);
		// return view('displaystats', compact('stats'));
	}

	public function displayFriends()
	{
		$friends = Friends :: where('person_1_id', '=', '10212631339123286')
					-> orWhere('person_2_id', '=', '10212631339123286')
					-> get();

		return response() -> json($friends);
	}

	public function displayGames()
	{
		return response() -> json();
	}
}
