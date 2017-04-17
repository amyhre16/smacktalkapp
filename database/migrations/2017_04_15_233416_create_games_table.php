<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('games', function(Blueprint $table) {
			$table -> increments('id');
			$table -> boolean('in_progress');
			$table -> string('whose_turn') -> references('people') -> on('id');
			$table -> string('winner_id') -> references('people') -> on('id');
			$table -> string('loser_id') -> references('people') -> on('id');
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
		Schema::dropIfExists('games');
    }
}
