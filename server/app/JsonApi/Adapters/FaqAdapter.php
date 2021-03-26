<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class FaqAdapter extends BaseAdapter
{

	/**
	 * Mapping of JSON API attribute field names to model keys.
	 *
	 * @var array
	 */
	protected $attributes = [];

	/*
	 * @var array
	 */
	protected $relationships = [
		'company',
		'createdBy',
	];

	/**
	 * Adapter constructor.
	 *
	 * @param StandardStrategy $paging
	 */
	public function __construct(StandardStrategy $paging)
	{
		parent::__construct(new \App\Models\Faq(), $paging);
	}

	/**
	 * @param Builder $query
	 * @param Collection $filters
	 * @return void
	 */
	protected function filter($query, Collection $filters)
	{
		$query->where('company_id', config('app.default_company_id'));
	}

	/*
	 * @return belongsTo Relation
	 */
	protected function company()
	{
		return $this->belongsTo();
	}

	protected function createdBy()
	{
		return $this->belongsTo();
	}

}
