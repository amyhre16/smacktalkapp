<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{	
	// @protected $table = 'people';

	public function games()
	{
		return $this -> hasMany('App\PersonGame');
	}

	public function friends()
	{
		return $this -> hasMany('App\Friends');
	}
}