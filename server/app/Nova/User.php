<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\MorphToMany;

class User extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = 'App\\Models\\User';

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
		'id', 'name', 'email',
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

			Gravatar::make(),

			Text::make('Name')
			->sortable()
			->rules('required', 'max:255'),

			Text::make('Username')
			->sortable()
			->rules('required', 'min:4', 'max:30')
			->creationRules('unique:users,username')
			->updateRules('unique:users,username,{{resourceId}}'),

			Text::make('Email')
			->sortable()
			->rules('required', 'email', 'max:254')
			->creationRules('unique:users,email')
			->updateRules('unique:users,email,{{resourceId}}'),

			Password::make('Password')
			->onlyOnForms()
			->creationRules('required', 'string', 'min:6')
			->updateRules('nullable', 'string', 'min:6'),

			HasOne::make('Person'),

			MorphToMany::make('Roles'),
			MorphToMany::make('Permissions'),

			BelongsToMany::make('Products', 'purchasedProducts', 'App\Nova\Product'),
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
			new Filters\UserRole,
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
		return [];
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
		$companies = null;
		$company = session('company') ?? null;

		if (!empty($company)) {
			$companies = collect($company->id);
		} else {
			$companies = $user->companies->pluck('id')->unique();
		}

		// has companies
		if (!empty($companies)) {

			$query->whereHas('companies', function ($query) use ($companies) {

				$query->whereIn('companies.id', $companies);

			});

			// not superadmin, because superadministrator can see all users
			if (!$user->hasRole('superadministrator')) {
				$query->whereDoesntHave('roles', function ($q) {
					$q->where('name', 'superadministrator');
				});
			}

			// company_owner only can be seen by :
			if (!$user->hasRole(['superadministrator', 'company_owner'])) {
				$query->whereDoesntHave('roles', function ($q) {
					$q->where('name', 'company_owner');
				});
			}

			// common users only can see theirself
			if (!$user->hasRole([
				'superadministrator',
				'company_owner',
				'administrator'
			])) {
				$query->where('id', $user->id);
			}

		} elseif ($companies->isEmpty()) {
			// doesn't has company

			// common users only can see theirself
			if (!$user->hasRole('superadministrator')) {
				$query->where('id', $user->id);
			}

		}

		return $query;
	}
}
