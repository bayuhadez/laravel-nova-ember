<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProductCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductCategoryPolicy
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
		return true;
	}

	/**
	 * Determine whether the user can view the product category.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\ProductCategory  $productCategory
	 * @return mixed
	 */
	public function view(User $user, ProductCategory $productCategory)
	{
		return ($user->hasRole([
			'superadministrator',
			'company_owner',
			'administrator',
		]));
	}

	/**
	 * Determine whether the user can create product categories.
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
	 * Determine whether the user can update the product category.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\ProductCategory  $productCategory
	 * @return mixed
	 */
	public function update(User $user, ProductCategory $productCategory)
	{
		return ($user->hasRole([
			'superadministrator',
			'company_owner',
			'administrator',
		]));
	}

	/**
	 * Determine whether the user can delete the product category.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\ProductCategory  $productCategory
	 * @return mixed
	 */
	public function delete(User $user, ProductCategory $productCategory)
	{
		return ($user->hasRole([
			'superadministrator',
			'company_owner',
		]));
	}

	/**
	 * Determine whether the user can restore the product category.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\ProductCategory  $productCategory
	 * @return mixed
	 */
	public function restore(User $user, ProductCategory $productCategory)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the product category.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\ProductCategory  $productCategory
	 * @return mixed
	 */
	public function forceDelete(User $user, ProductCategory $productCategory)
	{
		//
	}
}
