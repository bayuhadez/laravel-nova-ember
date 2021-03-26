<?php

namespace App\JsonApi\Adapters;

use App\Models\Banner;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BannerAdapter extends BaseAdapter
{

	/**
	 * Mapping of JSON API attribute field names to model keys.
	 *
	 * @var array
	 */
	protected $attributes = ['image'];

	/*
	 * @var array
	 */
	protected $relationships = [
		'product',
	];

	/**
	 * Adapter constructor.
	 *
	 * @param StandardStrategy $paging
	 */
	public function __construct(StandardStrategy $paging)
	{
		parent::__construct(new Banner(), $paging);
    }

    /*
	 * @return BelongsTo Relation
	 */
	protected function company()
	{
		return $this->belongsTo();
    }

	/*
	 * @return BelongsTo Relation
	 */
	protected function product()
	{
		return $this->belongsTo();
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
