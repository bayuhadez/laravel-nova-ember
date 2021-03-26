<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
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
		return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can view the company.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Company  $company
	 * @return mixed
	 */
	public function view(User $user, Company $company)
	{
		return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can create companies.
	 *
	 * @param  \App\Models\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can update the company.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Company  $company
	 * @return mixed
	 */
	public function update(User $user, Company $company)
	{
		return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can delete the company.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Company  $company
	 * @return mixed
	 */
	public function delete(User $user, Company $company)
	{
		return $user->hasRole(['superadministrator']);
	}

	/**
	 * Determine whether the user can restore the company.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Company  $company
	 * @return mixed
	 */
	public function restore(User $user, Company $company)
	{
	}

	/**
	 * Determine whether the user can permanently delete the company.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Company  $company
	 * @return mixed
	 */
	public function forceDelete(User $user, Company $company)
	{
	}
}
