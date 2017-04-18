<?php

namespace App;

class FriendsGame extends Model
{
	// @protected $table = 'friends_game';

	public $timestamps = false;
	
	public function cards()
	{
		return $this -> hasMany('App\FlippedStatus');
	}
}