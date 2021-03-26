<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
		return $user->hasRole(['superadministrator', 'company_owner', 'administrator']);
	}

	/**
	 * Determine whether the user can view the role.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Role  $role
	 * @return mixed
	 */
	public function view(User $user, Role $role)
	{
		return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can create roles.
	 *
	 * @param  \App\Models\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can update the role.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Role  $role
	 * @return mixed
	 */
	public function update(User $user, Role $role)
	{
		return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can delete the role.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Role  $role
	 * @return mixed
	 */
	public function delete(User $user, Role $role)
	{
		return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can restore the role.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Role  $role
	 * @return mixed
	 */
	public function restore(User $user, Role $role)
	{
		return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can permanently delete the role.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Role  $role
	 * @return mixed
	 */
	public function forceDelete(User $user, Role $role)
	{
		return $user->hasRole(['superadministrator']);
	}
}
