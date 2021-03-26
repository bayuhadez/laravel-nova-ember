<?php

namespace App\JsonApi\Adapters;

use App\Models\Order;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class OrderAdapter extends BaseAdapter
{

	/**
	 * Mapping of JSON API attribute field names to model keys.
	 *
	 * @var array
	 */
	protected $attributes = [];

	protected $fillable = ['gross_amount', 'status', 'company_id'];

	/*
	 * @var array
	 */
	protected $relationships = [
		'company',
		'orderDetails',
		'user',
		'voucher'
	];

	/**
	 * Adapter constructor.
	 *
	 * @param StandardStrategy $paging
	 */
	public function __construct(StandardStrategy $paging)
	{
		parent::__construct(new Order(), $paging);
	}

	/*
	 * @return belongsTo Relation
	 */
	protected function company()
	{
		return $this->belongsTo();
	}

	protected function orderDetails()
	{
		return $this->hasMany();
	}

	/*
	 * @return belongsToMany Relation
	 */
	protected function products()
	{
		return $this->hasMany();
	}

	/*
	 * @return belongsTo Relation
	 */
	protected function user()
	{
		return $this->belongsTo();
	}

	protected function voucher()
	{
		return $this->belongsTo();
	}

	protected function transaction()
	{
		return $this->hasOne('midtransTransaction');
	}

	/**
	 * @param Builder $query
	 * @param Collection $filters
	 * @return void
	 */
	protected function filter($query, Collection $filters)
	{
		$user = auth('api')->user();
		$company = $user->currentOrFirstCompany;

		$query
			->where('user_id', $user->id)
			->where('company_id', $company->id);
	}

}
