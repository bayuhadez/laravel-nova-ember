<?php

namespace App\Nova\Filters;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ProductStatus extends Filter
{
	/**
	 * The displayable name of the filter.
	 *
	 * @var string
	 */
	public $name = 'Product Status';

	/**
	 * Apply the filter to the given query.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  mixed  $value
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function apply(Request $request, $query, $value)
	{
		return $query->where('status', $value);
	}

	/**
	 * Get the filter's available options.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function options(Request $request)
	{
		return ProductService::getStatusFilterOptions()->all();
	}
}
