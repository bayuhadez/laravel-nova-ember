<?php

namespace App\Policies;

use App\Models\User;
use App\Models\License;
use Illuminate\Auth\Access\HandlesAuthorization;

class LicensePolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view the models license.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\License  $license
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		return ($user->hasRole([
			'superadministrator',
			'company_owner',
			'administrator',
			'mentor',
		]));
	}

	/**
	 * Determine whether the user can view the models license.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\License  $license
	 * @return mixed
	 */
	public function view(User $user, License $license)
	{
		return ($user->hasRole([
			'superadministrator',
			'company_owner',
			'administrator',
			'mentor',
		]));
	}

	/**
	 * Determine whether the user can create models licenses.
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
			'mentor',
		]));
	}

	/**
	 * Determine whether the user can update the models license.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\License  $license
	 * @return mixed
	 */
	public function update(User $user, License $license)
	{
		return ($user->hasRole([
			'superadministrator',
			'company_owner',
			'administrator',
		]));
	}

	/**
	 * Determine whether the user can delete the models license.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\License  $license
	 * @return mixed
	 */
	public function delete(User $user, License $license)
	{
		//
	}

	/**
	 * Determine whether the user can restore the models license.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\License  $license
	 * @return mixed
	 */
	public function restore(User $user, License $license)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the models license.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\License  $license
	 * @return mixed
	 */
	public function forceDelete(User $user, License $license)
	{
		//
	}
}
