<?php

namespace App\Observers;

use App\Models\Banner;

class BannerObserver
{
	/**
	 * Handle the product 'creating' event
	 *
	 * @param \App\Models\Banner
	 * @return void
	 */
	public function creating(Banner $banner)
	{
		$user = auth()->user();

		if (!empty($user)) {

			// set company_id
			if (
				empty($banner->company_id) &&
				!is_null($user->getCurrentCompanyId())
			) {
				$banner->company_id = $user->getCurrentCompanyId();
			}
		}
	}

	/**
	 * Handle the product "created" event.
	 *
	 * @param  \App\Models\Banner  $banner
	 * @return void
	 */
	public function created(Banner $banner)
	{

	}

	/**
	 * Handle the product "updated" event.
	 *
	 * @param  \App\Models\Banner  $banner
	 * @return void
	 */
	public function updated(Banner $banner)
	{
		//
	}

	/**
	 * Handle the product "deleted" event.
	 *
	 * @param  \App\Models\Banner  $banner
	 * @return void
	 */
	public function deleted(Banner $banner)
	{
		//
	}

	/**
	 * Handle the product "restored" event.
	 *
	 * @param  \App\Models\Banner  $banner
	 * @return void
	 */
	public function restored(Banner $banner)
	{
		//
	}

	/**
	 * Handle the product "force deleted" event.
	 *
	 * @param  \App\Models\Banner  $banner
	 * @return void
	 */
	public function forceDeleted(Banner $banner)
	{
		//
	}
}
