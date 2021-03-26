<?php

namespace App\Policies;

use App\Models\User;
use App\Models\User as UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any posts.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		return true;
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\User  $model
	 * @return mixed
	 */
	public function view(User $user, UserModel $model)
	{
		return (
			// base on roles
			$user->hasRole([
				'superadministrator', 'company_owner', 'administrator'
			]) ||
			// update itself
			$user->owns($model, 'id')
		);
	}

	/**
	 * Determine whether the user can create models.
	 *
	 * @param  \App\Models\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		return $user->hasRole(['superadministrator', 'company_owner', 'administrator']);
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\User  $model
	 * @return mixed
	 */
	public function update(User $user, UserModel $model)
	{
		return (
			// base on roles
			$user->hasRole([
				'superadministrator', 'company_owner', 'administrator'
			]) ||
			// update itself
			$user->owns($model, 'id')
		);
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\User  $model
	 * @return mixed
	 */
	public function delete(User $user, UserModel $model)
	{
		return $user->hasRole(['superadministrator', 'company_owner']);
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\User  $model
	 * @return mixed
	 */
	public function restore(User $user, UserModel $model)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\User  $model
	 * @return mixed
	 */
	public function forceDelete(User $user, UserModel $model)
	{
		//
	}
}
