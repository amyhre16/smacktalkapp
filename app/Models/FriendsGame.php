<?php

namespace App;

class FriendsGame extends Model
{
	// @protected $table = 'friends_game';

	public function cards()
	{
		return $this -> hasMany('App\FlippedStatus');
	}
}