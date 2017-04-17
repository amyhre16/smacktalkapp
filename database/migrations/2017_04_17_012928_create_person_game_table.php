<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('person_game', function(Blueprint $table) {
			$table -> increments('id');
			$table -> string('person_id') -> references('people') -> on('id');
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
		Schema::dropIfExists('person_game');
    }
}
