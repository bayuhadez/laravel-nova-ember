<?php

namespace App\Policies;

use App\Interfaces\ApprovableInterface;
use App\Models\User;
use App\Models\SeminarProductMeta;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeminarProductMetaPolicy
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
	 * Determine whether the user can view the product.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\SeminarProductMeta  $seminarProductMeta
	 * @return mixed
	 */
	public function view(User $user, SeminarProductMeta $seminarProductMeta)
	{
		return true;
	}

	/**
	 * Determine whether the user can create products.
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
	 * Determine whether the user can update the product.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\SeminarProductMeta  $seminarProductMeta
	 * @return mixed
	 */
	public function update(User $user, SeminarProductMeta $seminarProductMeta)
	{
		return (
			$user->hasRole(['superadministrator', 'company_owner', 'administrator'])
		);
	}

	/**
	 * Determine whether the user can delete the product.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\SeminarProductMeta  $seminarProductMeta
	 * @return mixed
	 */
	public function delete(User $user, SeminarProductMeta $seminarProductMeta)
	{
		return $user->hasRole(['superadministrator', 'company_owner', 'administrator']);
	}

	/**
	 * Determine whether the user can restore the product.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\SeminarProductMeta  $seminarProductMeta
	 * @return mixed
	 */
	public function restore(User $user, SeminarProductMeta $seminarProductMeta)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the product.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\SeminarProductMeta  $seminarProductMeta
	 * @return mixed
	 */
	public function forceDelete(User $user, SeminarProductMeta $seminarProductMeta)
	{
		//
    }
    
    public function addSeminarProductSponsor(User $user, SeminarProductMeta $seminarProductMeta)
    {
        return (count($seminarProductMeta->seminarProductSponsors) < 6);
    }

}
