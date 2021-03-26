<?php

namespace App\JsonApi\Adapters;

use App\Models\Voucher;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class VoucherAdapter extends BaseAdapter
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
		'orders'
	];

	/**
	 * Adapter constructor.
	 *
	 * @param StandardStrategy $paging
	 */
	public function __construct(StandardStrategy $paging)
	{
		parent::__construct(new Voucher(), $paging);
    }

    /*
	 * @return BelongsTo Relation
	 */
	protected function company()
	{
		return $this->belongsTo();
    }

	/*
	 * @return HasMany Relation
	 */
	protected function orders()
	{
		return $this->hasMany();
    }

	/**
	 * @param Builder $query
	 * @param Collection $filters
	 * @return void
	 */
	protected function filter($query, Collection $filters)
	{
		// TODO
		if ($filters->has('name')) {
			$query->where('vouchers.name', '=', $filters->get('name'));
			$query->where('vouchers.stock', '>', 0);
		}
	}

}
