<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlippedStatus extends Model
{
	@protected $table = 'flipped_status';

	public function cards()
	{
		return $this -> belongsTo('App\FriendsGame');
	}
}