<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friends extends Model
{
	@protected $table = 'friends';

	public function people()
	{
		return $this -> belongsTo('App\Person');
	}
}