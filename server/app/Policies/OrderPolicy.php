<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
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
	 * @param  \App\Models\Order  $order
	 * @return mixed
	 */
	public function view(User $user, Order $order)
	{
		if (
			$user->hasRole(['superadministrator', 'company_owner', 'administrator'])
			// the owner:
			|| $order->user_id == $user->id
		) {
			return true;
		}

		return false;
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
	 * @param  \App\Order  $order
	 * @return mixed
	 */
	public function update(User $user, Order $order)
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
	 * @param  \App\Order  $order
	 * @return mixed
	 */
	public function delete(User $user, Order $order)
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
	 * @param  \App\Order  $order
	 * @return mixed
	 */
	public function restore(User $user, Order $order)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the order.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Order  $order
	 * @return mixed
	 */
	public function forceDelete(User $user, Order $order)
	{
		//
	}

	/**
	 * Determine whether the user can get payment token
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Order  $order
	 * @return mixed
	 */
	public function getPaymentToken(User $user, Order $order)
	{
		return (
			$order->user_id == $user->id &&
			$order->status == Order::STATUS_PENDING
		);
	}
}
