<?php

namespace App\JsonApi\Adapters;

use App\Models\Sponsor;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SponsorAdapter extends BaseAdapter
{

	/**
	 * Mapping of JSON API attribute field names to model keys.
	 *
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * Adapter constructor.
	 *
	 * @param StandardStrategy $paging
	 */
	public function __construct(StandardStrategy $paging)
	{
		parent::__construct(new Sponsor(), $paging);
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

}
