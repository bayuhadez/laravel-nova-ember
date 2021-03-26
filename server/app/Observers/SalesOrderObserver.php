<?php

namespace App\Observers;

use App\Models\SalesOrder;
use App\Lib\Functions;
use Carbon\Carbon;

class SalesOrderObserver
{
	/**
	 * Handle the rack "creating" event.
	 *
	 * @param  \App\Models\SalesOrder  $salesOrder
	 * @return void
	 */
	public function creating(SalesOrder $salesOrder)
    {
        if (empty($salesOrder->number)) {
            $salesOrder->number = Functions::randomNumber(5);
        }

        $user = auth()->user();

        if (!empty($user)) {
            $salesOrder->created_by = $user->id;
        }

		if (empty($salesOrder->created_at)) {
			$salesOrder->created_at = Carbon::now();
		}
	}
}
