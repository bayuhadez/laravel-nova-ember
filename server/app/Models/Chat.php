<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
	protected $fillable = [
		'message',
	];

	public function chatRoom()
	{
		return $this->belongsTo('App\Models\ChatRoom');
	}

	public function sender()
	{
		return $this->belongsTo('App\Models\User', 'sender_user_id');
	}

	public function getSenderNameAttribute()
	{
		return $this->sender->displayName;
	}
}
