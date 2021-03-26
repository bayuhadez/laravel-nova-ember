<?php

namespace App\Observers;

use App\Models\ProductCategory;

class ProductCategoryObserver
{
	/**
	 * Handle the product 'creating' event
	 *
	 * @param \App\Models\ProductCategory
	 * @return void
	 */
	public function creating(ProductCategory $productCategory)
	{
		$user = auth()->user();

		if (!empty($user)) {

			// set company_id
			if (
				empty($productCategory->company_id) &&
				!is_null($user->getCurrentCompanyId())
			) {
				$productCategory->company_id = $user->getCurrentCompanyId();
			}

		}
	}
	/**
	 * Handle the product category "created" event.
	 *
	 * @param  \App\Models\ProductCategory  $productCategory
	 * @return void
	 */
	public function created(ProductCategory $productCategory)
	{
		//
	}

	/**
	 * Handle the product category "updated" event.
	 *
	 * @param  \App\Models\ProductCategory  $productCategory
	 * @return void
	 */
	public function updated(ProductCategory $productCategory)
	{
		//
	}

	/**
	 * Handle the product category "deleted" event.
	 *
	 * @param  \App\Models\ProductCategory  $productCategory
	 * @return void
	 */
	public function deleted(ProductCategory $productCategory)
	{
		//
	}

	/**
	 * Handle the product category "restored" event.
	 *
	 * @param  \App\Models\ProductCategory  $productCategory
	 * @return void
	 */
	public function restored(ProductCategory $productCategory)
	{
		//
	}

	/**
	 * Handle the product category "force deleted" event.
	 *
	 * @param  \App\Models\ProductCategory  $productCategory
	 * @return void
	 */
	public function forceDeleted(ProductCategory $productCategory)
	{
		//
	}
}
