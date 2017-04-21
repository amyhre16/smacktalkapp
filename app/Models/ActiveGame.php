<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ActiveGame extends Model
{
	protected $table = 'active_games';

	public $timestamps = false;
}