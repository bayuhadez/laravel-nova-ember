<?php

namespace App\JsonApi\Adapters;

use App\Models\Chat;
use App\Events\MessageSent;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder; use Illuminate\Support\Collection;

class ChatAdapter extends BaseAdapter
{

	/**
	 * Mapping of JSON API attribute field names to model keys.
	 *
	 * @var array
	 */
	protected $attributes = [];

	protected $relationships = [
		'chatRoom',
	];

	/**
	 * Adapter constructor.
	 *
	 * @param StandardStrategy $paging
	 */
	public function __construct(StandardStrategy $paging)
	{
		parent::__construct(new Chat(), $paging);
	}

	/**
	 * @param Builder $query
	 * @param Collection $filters
	 * @return void
	 */
	protected function filter($query, Collection $filters)
	{
		// TODO
	}

	protected function creating(Chat $chat, $resource)
	{
		$user = auth('api')->user();
		$chat->sender_user_id = $user->id;
	}

	protected function created(Chat $chat, $resource)
	{
		broadcast(new MessageSent($chat))->toOthers();
	}

	protected function chatRoom()
	{
		return $this->belongsTo();
	}

}
