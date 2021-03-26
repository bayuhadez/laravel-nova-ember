<?php

namespace App\Nova;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Order';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
	public static $search = [
		'id', 'user_id', 'company_id', 'status', 'gross_amount',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function fields(Request $request)
	{
		return [
			ID::make()->sortable(),

			Select::make('Status')
			->options(OrderService::getStatusOptions())
			->displayUsingLabels()
			->sortable()
			->rules('required'),

			Currency::make('Amount', 'gross_amount')
			->sortable()
			->rules('required'),

			BelongsTo::make('User')
			->searchable()
			->rules('required'),

			BelongsTo::make('Voucher')
			->nullable()
			->searchable(),

			Date::make('Created At')
			->format('YYYY/MM/DD')
			->hideWhenCreating()
			->sortable(),

			//BelongsTo::make('Company'),
			HasMany::make('OrderDetails'),
		];
	}

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

	/**
	 * Build a "relatable" query for the given resource.
	 *
	 * This query determines which instances of the model may be attached to other resources.
	 *
	 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function relatableQuery(NovaRequest $request, $query)
	{
		$user = auth()->user();

		if (!$user->hasRole('superadministrator')) {
			$query->where('id', $user->getCurrentCompanyId());
		}

		return $query;
	}

	/**
	 * Overriding method
	 * Build an "index" query for the given resource.
	 *
	 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function indexQuery(NovaRequest $request, $query)
	{
		$user = $request->user();

		if (!$user->hasRole(['superadministrator'])) {

			// only diplay current company
			$query->where('company_id', $user->currentCompanyId);

			// common users only can see theirself
			if (!$user->hasRole(['company_owner', 'administrator' ])) {
				$query->where('id', $user->id);
			}
		}

		return $query;
	}

}
