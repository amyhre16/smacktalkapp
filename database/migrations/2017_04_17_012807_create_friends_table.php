<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('friends', function(Blueprint $table) {
			$table -> increments('id');
			$table -> string('person_1_id') -> references('people') -> on('id');
			$table -> string('person_2_id') -> references('people') -> on('id');
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
		Schema::dropIfExists('friends');
    }
}
