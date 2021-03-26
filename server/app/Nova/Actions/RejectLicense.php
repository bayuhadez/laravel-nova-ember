<?php

namespace App\Nova\Actions;

use App\Models\License;
use App\Services\LicenseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;

class RejectLicense extends Action
{
	use InteractsWithQueue, Queueable, SerializesModels;

	public $onlyOnDetail = true;

	/**
	 * Perform the action on the given models.
	 *
	 * @param  \Laravel\Nova\Fields\ActionFields  $fields
	 * @param  \Illuminate\Support\Collection  $models
	 * @return mixed
	 */
	public function handle(ActionFields $fields, Collection $models)
	{
		foreach ($models as $model) {
			// 1. set status to `Rejected
			$licenseService = new LicenseService;
			$licenseService->reject($model, $fields->comment);

			$licenseService->sendEmailRefusal($model);
		}
	}

	/**
	 * Get the fields available on the action.
	 *
	 * @return array
	 */
	public function fields()
	{
		return [
			Text::make('Comment'),
		];
	}

	/**
	 * @override
	 * Determine if the action is executable for the given request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Database\Eloquent\Model  $model
	 * @return bool
	 */
	public function authorizedToRun(Request $request, $model)
	{
		$user = auth()->user();

		return (
			$model->status == License::STATUS_PROPOSED &&
			$user->hasRole([
				'administrator',
				'company_owner',
				'superadministrator',
			])
		);
	}
}
