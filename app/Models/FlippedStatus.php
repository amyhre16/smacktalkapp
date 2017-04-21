<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlippedStatus extends Model
{
	protected $table = 'flipped_status';
	
	public $timestamps = false;
	
	public function cards()
	{
		return $this -> belongsTo('App\FriendsGame', 'friends_game_id', 'friend_id');
	}

	public function friend()
	{
		return $this -> hasManyThrough('App\People', 'App\FriendsGame', 'friend_id', 'id', 'friends_game_id');
	}

	public function player(){
		return $this->belongsTo('App\People', 'id', 'player_id');
	}

}