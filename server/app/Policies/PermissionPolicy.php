<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
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
		/*
		return (
			$user->hasRole(['superadministrator|company_owner|administrator'])
		);
		 */
	}

	/**
	 * Determine whether the user can view the permission.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Permission  $permission
	 * @return mixed
	 */
	public function view(User $user, Permission $permission)
	{
		//return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can create permissions.
	 *
	 * @param  \App\Models\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		//return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can update the permission.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Permission  $permission
	 * @return mixed
	 */
	public function update(User $user, Permission $permission)
	{
		//return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can delete the permission.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Permission  $permission
	 * @return mixed
	 */
	public function delete(User $user, Permission $permission)
	{
		//return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can restore the permission.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Permission  $permission
	 * @return mixed
	 */
	public function restore(User $user, Permission $permission)
	{
		//return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can permanently delete the permission.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Permission  $permission
	 * @return mixed
	 */
	public function forceDelete(User $user, Permission $permission)
	{
		//return $user->hasRole(['superadministrator']);
	}
}
