<?php

namespace App\JsonApi\Adapters;

use App\Models\ChatRoom;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ChatRoomAdapter extends BaseAdapter
{

	/**
	 * Mapping of JSON API attribute field names to model keys.
	 *
	 * @var array
	 */
	protected $attributes = [];

	protected $relationships = [
		'chats',
	];

	/**
	 * Adapter constructor.
	 *
	 * @param StandardStrategy $paging
	 */
	public function __construct(StandardStrategy $paging)
	{
		parent::__construct(new ChatRoom(), $paging);
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

	protected function chats()
	{
		return $this->hasMany();
	}

}
