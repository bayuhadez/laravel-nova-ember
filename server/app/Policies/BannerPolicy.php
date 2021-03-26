<?php

namespace App\Policies;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BannerPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any posts.
	 *
	 * @param  \App\Models\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		return ($user->hasRole([
			'superadministrator',
			'company_owner',
			'administrator',
		]));
	}

	/**
	 * Determine whether the user can view the order.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Banner  $banner
	 * @return mixed
	 */
	public function view(User $user, Banner $banner)
	{
		$canView = false;

		if ($user->hasRole(['superadministrator', 'company_owner', 'administrator'])) {
			$canView = true;
		}

		return $canView;
	}

	/**
	 * Determine whether the user can create orders.
	 *
	 * @param  \App\Models\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		return ($user->hasRole([
			'superadministrator',
			'company_owner',
			'administrator',
		]));
	}

	/**
	 * Determine whether the user can update the order.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Banner  $banner
	 * @return mixed
	 */
	public function update(User $user, Banner $banner)
	{
		return ($user->hasRole([
			'superadministrator',
			'company_owner',
			'administrator',
		]));
	}

	/**
	 * Determine whether the user can delete the order.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Banner  $banner
	 * @return mixed
	 */
	public function delete(User $user, Banner $banner)
	{
		return ($user->hasRole([
			'superadministrator',
			'company_owner',
			'administrator',
		]));
	}

	/**
	 * Determine whether the user can restore the order.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Banner  $banner
	 * @return mixed
	 */
	public function restore(User $user, Banner $banner)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the order.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Banner  $banner
	 * @return mixed
	 */
	public function forceDelete(User $user, Banner $banner)
	{
		//
	}

}
