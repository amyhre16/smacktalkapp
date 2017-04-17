<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function(Blueprint $table) {
			$table -> string('id');
			$table -> string('name');
			$table -> string('picture');
			$table -> integer('wins');
			$table -> integer('losses');
			$table -> integer('currentStreak');
			$table -> integer('longestStreak');

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
		Schema::dropIfExists('people');
    }
}
