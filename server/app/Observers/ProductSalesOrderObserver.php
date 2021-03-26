<?php

namespace App\Observers;

use App\Models\ProductSalesOrder;

class ProductSalesOrderObserver
{
	/**
	 * Handle the ProductSalesOrder "creating" event.
	 *
	 * @param  \App\Models\ProductSalesOrder  $pso
	 * @return void
	 */
	public function creating(ProductSalesOrder $pso)
    {
		$pso->sub_total = $pso->calculateSubTotal();
	}
}
