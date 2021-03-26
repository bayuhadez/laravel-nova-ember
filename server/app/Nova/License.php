<?php

namespace App\Nova;

use App\Models\License as LicenseModel;
use App\Nova\Filters\LicenseStatus;
use App\Services\LicenseService;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class License extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = 'App\Models\License';

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
		'id',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function fields(Request $request)
	{
		$resourceId = $request->resourceId ?? null;

		return [
			ID::make()->sortable(),

			Text::make('Name')->sortable(),

			Text::make('Number')
			->sortable()
			->rules(
				'required',
				// custom validation
				function ($attribute, $value, $fail) use ($resourceId) {

					$license = LicenseModel::
						where('number', $value)
						->where(function ($q) use ($value, $resourceId) {
							// if request contains resourceId
							// SHOULD ignore License with the same resourceId
							if (!empty($resourceId)) {
								$q->where('id', '<>', $resourceId);
							}
						})
						// ignore rejected
						->where('status', '<>', LicenseModel::STATUS_REJECTED)
						->first();

					if (!is_null($license)) {
						$fail('The '.$attribute.' has already been taken.');
					}
				}
			),

			Date::make('Expiry Date')
			->sortable()
			->format('DD MMM YYYY')
			->rules('required'),

			Image::make('Photo')->disk('public')->path(LicenseModel::$filePath),

			Select::make('Status')->options(LicenseService::getStatusOptions())
			->displayUsingLabels()
			->exceptOnForms(),

			BelongsTo::make('User')
			->searchable()
			->rules('required'),
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
			new LicenseStatus,
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
	public function actions(Request $request, $id = null)
	{
		$user = auth()->user();

		return [
			// Approve
			(new Actions\ApproveLicense)->canSee(function ($request) use ($user) {
				return (
					$user->hasRole([
						'administrator',
						'company_owner',
						'superadministrator',
					])
				);
			}),
			(new Actions\RejectLicense)->canSee(function ($request) use ($user) {
				return (
					$user->hasRole([
						'administrator',
						'company_owner',
						'superadministrator',
					])
				);
			}),
		];
	}
}
