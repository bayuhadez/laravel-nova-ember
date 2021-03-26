<?php

namespace App\Nova\Actions;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;

class ApproveProduct extends Action
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
			if (in_array($model->status, [
				Product::STATUS_REJECTED,
				Product::STATUS_PROPOSED,
			])) {
				$model->status = Product::STATUS_APPROVED;
				$model->save();
			}
		}
	}

	/**
	 * Get the fields available on the action.
	 *
	 * @return array
	 */
	public function fields()
	{
		return [];
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
		return (in_array($model->status, [
			Product::STATUS_REJECTED,
			Product::STATUS_PROPOSED,
		]));
	}
}
