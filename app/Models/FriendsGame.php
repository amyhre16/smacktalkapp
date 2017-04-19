<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FriendsGame extends Model
{
	protected $table = 'friends_game';

	public $timestamps = false;
	
	public function cards()
	{
		return $this -> hasMany('App\FlippedStatus');
	}

	public function friends()
	{
		return $this -> belongsTo('App\People');
	}
}