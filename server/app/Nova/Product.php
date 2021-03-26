<?php

namespace App\Nova;

use App\Models\Product as ProductModel;
use App\Services\ProductService;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Http\Requests\NovaRequest;

class Product extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = 'App\Models\Product';

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'name';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id',
		'name',
		'price',
	];

	protected static $fileDirectoryPrefix = "Product";

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

			Text::make('Name')->sortable(),

			Currency::make('Price')->sortable(),

			Text::make('Status', function () {
				if (isset($this->id)) {
					return (ProductService::getStatusOptions())->get($this->status);
				} else {
					return null;
				}
			})->exceptOnForms(),

			NovaTinyMCE::make('Description')->sortable(),

			Image::make('Image')
				->disk(\App\Models\Product::$disk)
				->store(function ($request, $model) {
					return [
						'image' => $request->image->store(
							($model->id
								? $model::$fileDirectoryPrefix.'/'.$model->id
								: $model::$fileDirectoryPrefixTmp
							),
							$model::$disk
						),
					];
				}),

			BelongsTo::make(
				ProductCategory::singularLabel(),
				'productCategory'
			),

			BelongsTo::make('Company'),

			HasOne::make('Seminar Product Metas', 'seminarProductMeta'),
			HasOne::make('Product Banner', 'productBanner'),
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
		return [
			new Filters\ProductStatus,
		];
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
		$user = auth()->user();

		return [
			// Approve
			(new Actions\ApproveProduct)->canSee(function ($request) use ($user) {
				return $user->hasRole([
					'administrator',
					'superadministrator',
					'company_owner'
				]);
			}),
			// Reject
			(new Actions\RejectProduct)->canSee(function ($request) use ($user) {

				return $user->hasRole([
					'administrator',
					'superadministrator',
					'company_owner'
				]);

			})->canRun(function ($request, $product) {

				$user = auth()->user();

				return (
					$user->hasRole([
						'administrator',
						'superadministrator',
						'company_owner'
					]) &&
					$product->status == ProductModel::STATUS_PROPOSED
				);

			}),
			// Purchase Order Report
			(new Actions\ExportPurchaseOrderProduct)->canSee(function ($request) use ($user) {
				return $user->hasRole([
					'administrator',
					'superadministrator',
					'company_owner'
				]);
			}),
		];
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

			// display only current company
			$query->where('company_id', $user->getCurrentCompanyId());

		}

		return $query;
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
		$orderResourceLabel = Str::kebab(\App\Nova\Order::label());

		if ($request->resource == $orderResourceLabel) {
			$query
				->whereIn('status', [
					ProductModel::STATUS_APPROVED
				]);
		}
		return $query;
	}

}
