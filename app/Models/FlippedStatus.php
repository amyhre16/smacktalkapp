<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlippedStatus extends Model
{
	// @protected $table = 'flipped_status';
	public $timestamps = false;
	
	public function cards()
	{
		return $this -> belongsTo('App\FriendsGame');
	}
}