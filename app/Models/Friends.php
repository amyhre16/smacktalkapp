<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friends extends Model
{
	public $timestamps = false;

	public function people()
	{
		return $this -> belongsTo('App\Person');
	}
}