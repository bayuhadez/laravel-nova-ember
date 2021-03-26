<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\OrderService;

class OrderObserver
{
	private $orderService;

	public function __construct(OrderService $orderService)
	{
		$this->orderService = $orderService;
	}

	/**
	 * Handle the order 'creating' event
	 *
	 * @param \App\Models\Order
	 * @return void
	 */
	public function creating(Order $order)
	{
		$user = auth()->user();

		// auto set status = pending
		if (is_null($order->status)) {
			$order->status = Order::STATUS_PENDING;
		}

		// auto set user_id
		if (!empty($user)) {

			// set user_id as author
			if (empty($order->user_id)) {
				$order->user_id = $user->id;
			}

			// set company_id
			if (
				empty($order->company_id) &&
				!is_null($user->getCurrentCompanyId())
			) {
				$order->company_id = $user->getCurrentCompanyId();
			}
		}
	}

	/**
	 * Handle the order "created" event.
	 *
	 * @param  \App\Models\Order  $order
	 * @return void
	 */
	public function created(Order $order)
	{
		//
	}

	/**
	 * Handle the order "updated" event.
	 *
	 * @param  \App\Models\Order  $order
	 * @return void
	 */
	public function updated(Order $order)
	{
		$original = $order->getOriginal();

		// if current status is Completed AND previous status is NOT Completed
		// Attach relationship between Order's Products to Order's User
		if (
			$order->status != $original['status'] &&
			$order->status == Order::STATUS_COMPLETED
		) {
			$this->orderService->attachProductsToUser($order);
		}
	}

	/**
	 * Handle the order "deleted" event.
	 *
	 * @param  \App\Models\Order  $order
	 * @return void
	 */
	public function deleted(Order $order)
	{
		//
	}

	/**
	 * Handle the order "restored" event.
	 *
	 * @param  \App\Models\Order  $order
	 * @return void
	 */
	public function restored(Order $order)
	{
		//
	}

	/**
	 * Handle the order "force deleted" event.
	 *
	 * @param  \App\Models\Order  $order
	 * @return void
	 */
	public function forceDeleted(Order $order)
	{
		//
	}
}
