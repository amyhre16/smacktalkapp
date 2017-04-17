<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */	
    public function up()
    {
        //
		Schema::create('friends_game', function(Blueprint $table) {
			$table -> increments('id');
			$table -> string('friend_id') -> references('people') -> on('id');
			$table -> integer('game_id') -> references('games') -> on('id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
		Schema::dropIfExists('friends_game');
    }
}
