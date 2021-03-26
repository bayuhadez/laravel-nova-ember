<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
	/**
	 * Return sorted list of Order's status
	 *
	 * @return Collection contains [
	 *   (int)Status's id => (string)Status's name
	 * ]
	 */
	public static function getStatusOptions()
	{
		return collect([
			Order::STATUS_PENDING => 'Pending',
			Order::STATUS_FAILED => 'Failed',
			Order::STATUS_PROCESSING => 'Processing',
			Order::STATUS_COMPLETED => 'Completed',
			Order::STATUS_ONHOLD => 'On Hold',
			Order::STATUS_CANCELLED => 'Cancelled',
			Order::STATUS_REFUNDED => 'Refunded',
		])->sort();
	}

	/**
	 * Assign Product to User based on Order
	 *
	 * @return void
	 */
	public function assignProductUserBaseOnOrder(Order $order)
	{
		$user = $order->user;

		$products = $order->products;

		$products->each(function ($product, $key) use ($user) {
			$user->products()->attach($product->id);
		});
	}

	/**
	 * Change status order
	 *
	 * @param int $orderId
	 * @param int $status
	 *
	 * @return Order
	 */
	public function changeStatus($orderId, $status)
	{
		$order = Order::find($orderId);
		$order->status = $status;
		$order->save();
	}

	/**
	 * Attach Order's Products to Order's User
	 *
	 * @param Order $order
	 *
	 * @return void
	 */
	public function attachProductsToUser(Order $order)
	{
		// get product's id
		$productIds = $order->products->unique()->pluck('id')->all();

		// get user
		$user = $order->user;

		// attach products to user
		$user->purchasedProducts()->attach($productIds);
	}

	/**
	 * creates an order record and order details based on the collection
	 * of Product $products
	 */
	static public function createOrderByProductsForUser($products, $user, $voucher = null)
	{
		$order = Order::create([
			'user_id' => $user->id,
			'company_id' => $user->currentOrFirstCompany->id,
			'voucher_id' => (!empty($voucher) ? $voucher->id : null)
		]);

		$grossAmount = 0;

		// create a product detail for each product
		foreach ($products as $product) {
			$price = $product->price;
			$quantity = 1;

			$order->orderDetails()->create([
				'product_id' => $product->id,
				'price' => $price,
				'quantity' => $quantity,
				'name' => $product->name,
				'category' => $product->productCategory->name ?? null,
			]);

			$grossAmount += ($price * $quantity);
		}

		if (!empty($voucher)) {
			$grossAmount -= $voucher->amount;
			$voucherStock = $voucher->stock - 1;
			$voucher->update(['stock' => $voucherStock]);
		}

		$order->update(['gross_amount' => $grossAmount]);

		return $order;
	}

	public function generatePurchaseOrderReportForProduct($product)
	{
		$collections = collect();
		$totalAmount = 0;
		$orderDetails = $product->orderDetails;

		$filteredOrderDetails = $orderDetails->filter(function ($orderDetail, $key) {

			if (!empty($orderDetail->order)) {
				return $orderDetail->order->status == Order::STATUS_COMPLETED;
			}

			return false;
		});

		// header
		$collections->push(collect([
			'Tanggal',
			'Order ID',
			'Pembeli',
			'Nominal',
		]));

		// rows
		foreach ($filteredOrderDetails as $orderDetail) {

			$amount = $orderDetail->price * $orderDetail->quantity;

			$row = collect([
				'date' => $orderDetail->created_at->toDateString(),
				'orderId' => $orderDetail->order->paymentId,
				'name' => $orderDetail->order->user->displayName,
				'amount' => $amount,
			]);

			$totalAmount += $amount;

			$collections->push($row);
		}

		// summary
		$collections->push(collect([null, null]));
		$collections->push(collect([
			'Total',
			$totalAmount,
		]));

		$collections->push(collect([
			'Komisi',
			$totalAmount * 40 / 100,
		]));

		return $collections;
	}

}
