<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

class Role extends Resource
{
	/**
	 * Hide from resource sidebar
	 *
	 * @var boolean
	 */
	public static $displayInNavigation = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Role';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'display_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'display_name'
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
            Text::make('Name')
                ->hideFromIndex()
                ->rules('required', 'max:191'),
            Text::make('Display Name')
                ->sortable()
                ->rules('required', 'max:255'),
            Textarea::make('Description') 
                ->hideFromIndex()
                ->rules('required', 'max:191'),
			BelongsToMany::make('Permissions'),
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

			$query->where('name', '<>', 'superadministrator');
			$query->where('name', '<>', 'company_owner');

			if ($user->hasRole('administrator')) {
				$query->where('name', '<>', 'administrator');
			}

		}

		// if resource is Users
		// only display available roles
		if ($request->resource == strtolower(\App\Nova\User::label())) {

			$query->whereDoesntHave('users', function ($query) use ($request) {
				$query->where('id', $request->resourceId);
			});

		}

		return $query;
	}
}
