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
		$relationships = Friends :: where('person_1_id', '=', $request -> user_id)
					-> orWhere('person_2_id', '=', $request -> user_id)
					-> get();

		$friends = [];

		foreach ($relationships as $friend) {
			if ($friend -> person_1_id == $request -> user_id) {
				array_push($friends, $friend -> person_2_id);
			}

			else {
				array_push($friends, $friend -> person_1_id);
			}
		}

		$friendsInfo = People :: whereIn('id', $friends) -> get();
		
		return response() -> json($friendsInfo);
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
		if (count($newFriends) > 0) {
			foreach ($friendsList as $friend) {
				array_push($newFriends, ['person_1_id' => $request -> newUser['id'], 'person_2_id' => $friend['id']]);
			}

			Friends :: insert($newFriends);
		}
		
		return response() -> json($newFriends);
	}


	// parameters are game id and winner/loser ids
	public function finishGame(Request $request) {
		// $game = Game :: where('id', $request -> game_id) -> get();
		$game = Game :: where('id', $request -> game_id) -> update(['in_progress' => 0, 'winner_id' => $request -> winner_id, 'loser_id' => $request -> loser_id]);
		// $game -> in_progress = 0;
		// $game -> winner_id = $request -> winner_id;
		// $game -> loser_id = $request -> loser_id;

		// $game -> save();
		
		return response() -> json($game);
	}

	// parameters are player ids and whose turn it is (i.e., who created the game)
	public function createGame(Request $request) {
		$newGame = new Game();

		$newGame -> whose_turn = $request['whose_turn'];

		$newGame -> save();

		$newPersonGame = [['person_id' => $request -> players[0], 'game_id' => $newGame -> id],
			['person_id' => $request -> players[1], 'game_id' => $newGame -> id]];

		PersonGame :: insert($newPersonGame);

		// this retrieves the players' relationships
		$cards = Friends :: whereIn('person_1_id', $request -> players)
			-> orWhereIn('person_2_id', $request -> players)
			-> get();

		// assuming the players will not be cards, want to filter out players' ids so that we only have their friends
		$friends = [];
		$friend_ids = [];
		$newFriends = [];

		foreach ($cards as $card) {
			if (!in_array($card -> person_1_id, $request -> players) && !in_array($card -> person_1_id, $newFriends)) {
				array_push($friends, ['friend_id' => $card -> person_1_id, 'game_id' => $newGame -> id]);
				array_push($friend_ids, ['id' => $card -> person_1_id]);
				array_push($newFriends, $card -> person_1_id);
			}

			else if (!in_array($card -> person_2_id, $request -> players) && !in_array($card -> person_1_id, $newFriends)) {
				array_push($friends, ['friend_id' => $card -> person_2_id, 'game_id' => $newGame -> id]);
				array_push($friend_ids, ['id' => $card -> person_2_id]);
				array_push($newFriends, $card -> person_2_id);
			}
		}

		FriendsGame :: insert($friends);
		
		$flipped_status = [];

		$newFriendsGame = FriendsGame :: where('game_id', '=', $newGame -> id) -> get();

		foreach ($newFriendsGame as $card) {
			array_push($flipped_status, ['friends_game_id' => $card -> id, 'player_id' => $request -> players[0]]);
			array_push($flipped_status, ['friends_game_id' => $card -> id, 'player_id' => $request -> players[1]]);
		}

		FlippedStatus :: insert($flipped_status);

		// $cardInfo = People :: whereIn('id', $friend_ids) -> get();
		$newCards = $newFriendsGame -> friends() -> join('people', 'people.id', '=', 'friends_game.friend_id') -> get();
		
		// want to return associative array that contains friend. Don't need to include flipped status b/c it's a new game therefore all cards are not flipped
		return response() -> json($newCards);
		// return response() -> json(['friends_game' => $friends, 'flipped_status' => $flipped_status]);
	}
}