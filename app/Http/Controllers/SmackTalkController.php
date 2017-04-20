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
	/*
		parameter is user's id
		ex:
			route?user_id=10212631339123286
	*/
    public function displayStats(Request $request)
	{
		$stats = People :: where('id', '=', $request -> user_id)
					-> get();

		return response() -> json($stats);
	}

	/*
		parameter is user's id
		ex:
			route?user_id=10212631339123286
	*/
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

	/*
		parameters are user object and array of friend objects
		example
			{
				"newUser": {
					"name": "Alex Slayton",
					"id": "10213667577749724",
					"picture": {
						"data": {
							"height": 480,
							"is_silhouette": false,
							"url": "https://scontent.xx.fbcdn.net/v/t1.0-1/p480x480/1380798_10202475188066977_33540289_n.jpg?oh=746baee298461057e7c0739201c5d900&oe=59963C2F",
							"width": 480
						}
					}
				},
				"friends_list": [
					{
						"name": "Ian Shirley",
						"id": "10154210129687000",
						"picture": {
							"data": {
								"height": 359,
								"is_silhouette": false,
								"url": "https://scontent.xx.fbcdn.net/v/t1.0-1/17799271_10154202452472000_7553236793587612217_n.jpg?oh=ba24a40b43395306461f7aa7d5344747&oe=599AF32F",
								"width": 372
							}
						}
					},
					{
						"name": "Latisha McNeel",
						"id": "10155316755764429",
						"picture": {
							"data": {
								"height": 480,
								"is_silhouette": false,
								"url": "https://scontent.xx.fbcdn.net/v/t1.0-1/p480x480/17799252_10155294412174429_8990644720946975186_n.jpg?oh=a9086e532ea378f0cef575b3031aea74&oe=5988151E",
								"width": 480
							}
						}
					}
				]
			}
	*/
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


	/*
		parameters are game id and winner/loser ids
		example
			{
				"game_id": 22,
				"winner_id": "10154550340256172",
				"loser_id": "10154210129687000"
			}
	*/
	public function finishGame(Request $request) {
		$game = Game :: where('id', $request -> game_id)
			-> update(['in_progress' => 0, 'winner_id' => $request -> winner_id, 'loser_id' => $request -> loser_id]);
		
		return response() -> json($game);
	}

	/*
		parameters are player ids and whose turn it is (i.e., who created the game)
		example:
			{
				"players": ["10154210129687000", "10154550340256172"],
				"whose_turn": "10154210129687000"
			}
	*/
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


	/*
		parameters are user id and array of friend objects
		example
			{
				"user_info": {
     				"name": "Anthony Myhre",
					"id": "1624671107562854",
					"picture": {
						"data": {
							"height": 480,
							"is_silhouette": false,
							"url": "https://scontent.xx.fbcdn.net/v/t1.0-1/s480x480/14702276_1411905718839395_8516470454163265900_n.jpg?oh=8199abad883e905536db2cfc1732fd2e&oe=5984E748",
							"width": 479
						}
					}
				},
				"friends_list": [
					{
						"name": "Ian Shirley",
						"id": "10154210129687000",
						"picture": {
							"data": {
								"height": 359,
								"is_silhouette": false,
								"url": "https://scontent.xx.fbcdn.net/v/t1.0-1/17799271_10154202452472000_7553236793587612217_n.jpg?oh=ba24a40b43395306461f7aa7d5344747&oe=599AF32F",
								"width": 372
							}
						}
					},
					{
						"name": "Latisha McNeel",
						"id": "10155316755764429",
						"picture": {
							"data": {
								"height": 480,
								"is_silhouette": false,
								"url": "https://scontent.xx.fbcdn.net/v/t1.0-1/p480x480/17799252_10155294412174429_8990644720946975186_n.jpg?oh=a9086e532ea378f0cef575b3031aea74&oe=5988151E",
								"width": 480
							}
						}
					}
				]
			}
	*/
	public function updateUserInfo(Request $request) {
		
		$friendsList = $request -> friends_list;
		// return response() -> json($friendsList);
		$user = $request -> user_info;
		$user_id = $user['id'];
		$user_name = $user['name'];
		$user_picture = $user['picture']['data']['url'];

		// if the friends list is not empty
		// need to grab user's friends and determine what friends from graph api are new
		// once new friends have been determined, add relationships 
		if (count($friendsList) > 0) {
			// grab the user's current friends
			$currFriends = Friends :: where('person_1_id', '=', $user_id)
				-> orWhere('person_2_id', '=', $user_id)
				-> get();
			
			// this is the array in which the new friends' ids will be stored
			$newFriends = [];

			// this is an array in which the user's friends' ids will be stored
			$currFriendIds = [];

			// if the user does have friends, store their ids in the array
			if (count($currFriends) > 0) {
				foreach ($currFriends as $friend) {
					if ($friend -> person_1_id == $user_id) {
						array_push($currFriendIds, $friend -> person_2_id);
					}

					else {
						array_push($currFriendIds, $friend -> person_1_id);
					}
				}


				foreach($friendsList as $friend) {
					// if this friend is NOT already a friend, then add their id to the array
					if (!in_array($friend['id'], $currFriendIds)) {
						array_push($newFriends, ['person_1_id' => $user_id, 'person_2_id' => $friend['id']]);
					}
				}
			}

			else {
				foreach($friendsList as $friend) {
					array_push($newFriends, ['person_1_id' => $user_id, 'person_2_id' => $friend['id']]);
				}
			}
			// return response() -> json($newFriends);
			Friends :: insert($newFriends);
		}

		$currentUserInfo = People :: where('id', '=', $user_id) -> get()[0];
		// return response() -> json($currentUserInfo);
		$currentName = $currentUserInfo['name'];
		$currentPicture =  $currentUserInfo['picture'];
		

		if (($currentName != $user_name) && ($currentPicture != $user_picture)) {
			$currentName = $user_name;
			$currentPicture = $user_picture;

			People :: where('id', '=', $user_id)
				-> update(['name' => $currentName, 'picture' => $currentPicture]);
		}

		else if ($currentName != $user_name) {
			$currentName = $user_name;
			People :: where('id', '=', $user_id)
				-> update(['name' => $currentName]);
		}

		else if ($currentPicture != $user_picture) {
			$currentPicture = $user_picture;
			People :: where('id', '=', $user_id)
				-> update(['picture' => $currentPicture]);
		}

		return response() -> json(['name' => $currentName, 'picture' => $currentPicture]);
	}

	/*
		parameters will be game id, user id, array of cards that got flipped
		example
			{
				"game_id": 24,
				"user_id": ""
			}
	*/
}