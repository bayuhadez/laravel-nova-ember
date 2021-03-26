<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;

class SeminarProductMeta extends Resource
{
	public static $displayInNavigation = false;

	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = 'App\Models\SeminarProductMeta';

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

	protected static $fileDirectoryPrefix = 'SeminarProductMeta';

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
			BelongsTo::make('Product'),
			BelongsTo::make('Speaker', 'speaker', 'App\Nova\User')->searchable(),
			DateTime::make('Start Time')->sortable(),
			DateTime::make('End Time')->sortable(),
			Text::make('Stream Key', 'stream_key')->exceptOnForms(),
			File::make('Power Point File', 'powerpoint_file_path')
				->store(function ($request, $model) {
					return [
						'powerpoint_file_path' => $request->powerpoint_file_path->store(
							($model->id
								? $model::$fileDirectoryPrefixPowerpoint.'/'.$model->id
								: $model::$fileDirectoryPrefixTmp
							),
							config('filesystems.cloud')
						),
					];
				})
				->disk(config('filesystems.cloud')),
			File::make('Playback Video File', 'playback_video_file_path')
				->store(function ($request, $model) {
					return [
						'playback_video_file_path' => $request->playback_video_file_path->store(
							($model->id
								? $model::$fileDirectoryPrefixPlaybackVideo.'/'.$model->id
								: $model::$fileDirectoryPrefixTmp
							),
							config('filesystems.cloud')
						),
					];
				})
				->disk(config('filesystems.cloud')),
			File::make('Broadcast Video File', 'broadcast_video_path')
				->store(function ($request, $model) {
					return [
						'broadcast_video_path' => $request->broadcast_video_path->store(
							($model->id
								? $model::$fileDirectoryPrefixBroadcastVideo.'/'.$model->id
								: $model::$fileDirectoryPrefixTmp
							),
							config('filesystems.cloud')
						),
					];
				})
				->disk(config('filesystems.cloud')),
			HasMany::make(
				SeminarProductSponsor::label(),
				'seminarProductSponsors'
			),
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
		return [
			// start seminar
			(new Actions\StartSeminar),
		];
	}

	/**
	 * Get the displayable singular label of the resource.
	 *
	 * @return string
	 */
	public static function singularLabel()
	{
		return __('Seminar Product Meta');
	}

	public static function label()
	{
		// Product Categories
		return __(Str::plural(self::singularLabel()));
	}
}
