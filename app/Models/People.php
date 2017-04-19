<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{	
	public $timestamps = false;

	public function games()
	{
		return $this -> hasMany('App\PersonGame');
	}

	public function friends()
	{
		return $this -> hasMany('App\Friends');
	}

	public function cards()
	{
		return $this -> hasMany('App\FriendsGame');
	}
}