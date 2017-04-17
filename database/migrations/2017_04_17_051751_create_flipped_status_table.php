<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlippedStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('flipped_status', function(Blueprint $table) {
			$table -> increments('id');
			$table -> integer('friends_game_id') -> references('friends_game') -> on ('id');
			$table -> string('player_id') -> references('people');
			$table -> boolean('flipped_status') -> default(false);
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
		Schema::dropIfExists('flipped_status');
    }
}
