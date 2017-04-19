<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonGame extends Model
{
	protected $table = 'person_game';

	public $timestamps = false;

	public function games()
	{
		return $this -> belongsTo('App\Person');
	}
}