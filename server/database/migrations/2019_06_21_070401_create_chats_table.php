<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chats', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('sender_user_id')->unsigned();
			$table->integer('chat_room_id')->unsigned();
			$table->string('message');
			$table->timestamps();

			$table->foreign('sender_user_id')
				->references('id')
				->on('users')
				->onDelete('cascade')
				->onUpdate('cascade');

			$table->foreign('chat_room_id')
				->references('id')
				->on('chat_rooms')
				->onDelete('cascade')
				->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('chats');
	}
}
