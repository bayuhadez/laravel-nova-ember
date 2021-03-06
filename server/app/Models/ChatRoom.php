<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $table = 'chat_rooms';

	public function chats()
	{
		return $this->hasMany('App\Models\Chat');
	}
}
