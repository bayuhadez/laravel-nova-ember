<?php

namespace App\Services;

use App\Models\MidtransTransaction;
use App\Models\Order;

class MidtransService
{
	public function __construct()
	{
		// Set your Merchant Server Key
		\Midtrans\Config::$serverKey = config('midtrans.server_key');
		// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
		\Midtrans\Config::$isProduction = config('midtrans.is_production');
		// Set sanitization on (default)
		\Midtrans\Config::$isSanitized = true;
		// Set 3DS transaction for credit card to true
		\Midtrans\Config::$is3ds = true;
	}

	/**
	 * Return array of transaction for getSnapToken() from an order
	 *
	 * @param Order $order
	 *
	 * @return array can contain [
	 *   'transaction_details'
	 *   'customer_details'
	 *   'item_details'
	 * ]
	 */
	private function getSnapTokenTransaction(Order $order)
	{
		$transaction_details = [
			'order_id' => config('app.payment_id_prefix').$order->id,
			'gross_amount' => $order->gross_amount,
		];

		// Populate items
		$items = [];
		foreach ($order->orderProducts as $orderProduct) {
			$items[] = [
				'id'        => $orderProduct->id,
				'price'     => $orderProduct->price,
				'quantity'  => $orderProduct->quantity,
				'name'      => $orderProduct->name,
			];
		}

		// Populate customer's billing address
		$billing_address = [
			'first_name'    => $order->user->name,
			'last_name'     => "",
			'address'       => "",
			'city'          => "",
			'postal_code'   => "",
			'phone'         => "",
			'country_code'  => 'IDN'
		];
		// Populate customer's shipping address
		$shipping_address = [
			'first_name'    => $order->user->name,
			'last_name'     => "",
			'address'       => "",
			'city'          => "",
			'postal_code'   => "",
			'phone'         => "",
			'country_code'  => 'IDN'
		];

		// Populate customer's Info
		$customer_details = [
			'first_name'      => $order->user->name,
			'last_name'       => "",
			'email'           => $order->user->email,
			'phone'           => "",
			'billing_address' => $billing_address,
			'shipping_address'=> $shipping_address
		];

		// Data yang akan dikirim untuk request redirect_url.
		$credit_card['secure'] = true;
		//set save_card true to enable oneclick or 2click
		//$credit_card['save_card'] = true;
		$time = time();
		$custom_expiry = [
			'start_time' => date("Y-m-d H:i:s O",$time),
			'unit'       => 'hour',
			'duration'   => 2
		];

		$transaction_data = [
			'transaction_details'=> $transaction_details,
			'item_details'       => $items,
			'customer_details'   => $customer_details,
			'credit_card'        => $credit_card,
			'expiry'             => $custom_expiry
		];

		return $transaction_data;
	}

	/**
	 * Return array minimal data of transaction for getSnapToken() from an order
	 *
	 * @param Order $order
	 *
	 * @return array can contain [
	 *   'transaction_details'
	 *   'customer_details'
	 *   'credit_card'
	 *   'expiry'
	 * ]
	 */
	private function getSnapTokenTransactionMinimalData(Order $order)
	{
		$transaction_details = [
			'order_id' => config('app.payment_id_prefix').$order->id,
			'gross_amount' => $order->gross_amount,
		];

		// Populate customer's billing address
		$billing_address = [
			'first_name'    => $order->user->name,
			'last_name'     => "",
			'address'       => "",
			'city'          => "",
			'postal_code'   => "",
			'phone'         => "",
			'country_code'  => 'IDN'
		];
		// Populate customer's shipping address
		$shipping_address = [
			'first_name'    => $order->user->name,
			'last_name'     => "",
			'address'       => "",
			'city'          => "",
			'postal_code'   => "",
			'phone'         => "",
			'country_code'  => 'IDN'
		];

		// Populate customer's Info
		$customer_details = [
			'first_name'      => $order->user->name,
			'last_name'       => "",
			'email'           => $order->user->email,
			'phone'           => "",
			'billing_address' => $billing_address,
			'shipping_address'=> $shipping_address
		];

		// Data yang akan dikirim untuk request redirect_url.
		$credit_card['secure'] = true;
		//set save_card true to enable oneclick or 2click
		//$credit_card['save_card'] = true;
		$time = time();
		$custom_expiry = [
			'start_time' => date("Y-m-d H:i:s O",$time),
			'unit'       => 'hour',
			'duration'   => 2
		];

		$transaction_data = [
			'transaction_details'=> $transaction_details,
			'customer_details'   => $customer_details,
			'credit_card'        => $credit_card,
			'expiry'             => $custom_expiry
		];

		return $transaction_data;
	}

	/**
	 * Get Snap Token from an Order
	 *
	 * @param App\Models\Order $order
	 *
	 * @return string (36 chars)
	 */
	public function getSnapToken(Order $order)
	{
		$transaction_data = $this->getSnapTokenTransactionMinimalData($order);

		return \Midtrans\Snap::getSnapToken($transaction_data);
	}

	/**
	 * Return system orderId from midtransOrderId
	 *
	 * @param string $paymentGatewayOrderId
	 *
	 * @return string|null
	 */
	public function getOrderIdFromPaymentGatewayOrderId($paymentGatewayOrderId)
	{
		$orderId = substr(strrchr($paymentGatewayOrderId, "-"), 1);

		if (is_numeric($orderId)) {
			return $orderId;
		}
		return null;
	}

	/**
	 * Update:
	 *  1 Payment status
	 *  2 Order status
	 *  3 Save midtrans transaction
	 *
	 * @param string $paymentGatewayOrderId
	 *
	 * @return \App\Models\Order|null
	 */
	public function updateOrderBasedOnPaymentGatewayOrder($paymentGatewayOrderId)
	{
		// get payment gateway data from midtrans
		$paymentGatewayData = $this->getPaymentGatewayData($paymentGatewayOrderId);

		// get order
		$orderId = $this->getOrderIdFromPaymentGatewayOrderId($paymentGatewayData->order_id);

		$order = Order::find($orderId);

		if (!is_null($order)) {

			// update order's payment_status
			$this->updateOrderPaymentStatus($order, $paymentGatewayData);

			// update order's status
			$this->updateOrderStatus($order, $paymentGatewayData);

			// update/create paymentGatewayTransaction
			$this->savePaymentGatewayTransaction($paymentGatewayData);

			return $order;
		}

		return null;
	}

	/**
	 * Update Order's payment_status
	 *
	 * @param App\Models\Order $order
	 * @param Object $notification
	 *
	 * @return void
	 */
	private function updateOrderPaymentStatus(Order &$order, $notification)
	{
		$transaction = $notification->transaction_status;

		$type = $notification->payment_type;

		$fraud = $notification->fraud_status;

		// set payment_status
		if ($transaction == 'capture') {

			// For credit card transaction, we need to check whether transaction is challenge by FDS or not
			if ($type == 'credit_card') {

				if ($fraud == 'challenge') {
					$order->payment_status = Order::PAYMENT_STATUS_CHALLENGED;
				} else {
					$order->payment_status = Order::PAYMENT_STATUS_SUCCESS;
				}

			}

		} else if ($transaction == 'settlement') {

			$order->payment_status = Order::PAYMENT_STATUS_SETTLEMENT;

		} else if($transaction == 'pending') {

			$order->payment_status = Order::PAYMENT_STATUS_PENDING;

		} else if ($transaction == 'deny') {

			$order->payment_status = Order::PAYMENT_STATUS_DENIED;

		} else if ($transaction == 'expire') {

			// set payment status in merchant's database to 'expire'
			$order->payment_status = Order::PAYMENT_STATUS_EXPIRED;

		} else if ($transaction == 'cancel') {

			// set payment status in merchant's database to 'Denied'
			$order->payment_status = Order::PAYMENT_STATUS_DENIED;
		}

		// save order's payment_status
		$order->save();
	}

	/**
	 * Update Order's status
	 *
	 * @param App\Models\Order $order
	 * @param Object $notification
	 *
	 * @return void
	 */
	private function updateOrderStatus(Order &$order, $notification)
	{
		$transaction = $notification->transaction_status;

		$type = $notification->payment_type;

		$fraud = $notification->fraud_status;

		// Set Order's status
		if ($transaction == 'capture') {
			// For credit card transaction, we need to check whether transaction is challenge by FDS or not
			if ($type == 'credit_card') {

				if ($fraud == 'challenge') {

					// set payment status in merchant's database to 'Challenge by FDS'
					// merchant should decide whether this transaction is authorized or not in MAP
					//$order->status = Order::STATUS_ONHOLD;

				} else {
					// set payment status in merchant's database to 'Success'
					$order->status = Order::STATUS_COMPLETED;

				}
			}

		} else if ($transaction == 'settlement') {
			// set payment status in merchant's database to 'Settlement'
			// $order->status = Order::STATUS_ONHOLD;
			$order->status = Order::STATUS_COMPLETED;

		} else if($transaction == 'pending') {

			// set payment status in merchant's database to 'Pending'
			$order->status = Order::STATUS_PENDING;

		} else if ($transaction == 'deny') {

			// set payment status in merchant's database to 'Denied'
			$order->status = Order::STATUS_FAILED;

		} else if ($transaction == 'expire') {
			// set payment status in merchant's database to 'expire'
			$order->status = Order::STATUS_FAILED;

		} else if ($transaction == 'cancel') {

			// set payment status in merchant's database to 'Denied'
			$order->status = Order::STATUS_CANCELLED;

		} else {
			$order->status = Order::STATUS_FAILED;
		}

		// save order's status
		$order->save();
	}

	/**
	 * Get url string source file of snap.js
	 *
	 * @return string source file
	 */
	public function getSnapJavascriptSrc()
	{
		if (config('midtrans.is_production')) {
			return "https://app.midtrans.com/snap/snap.js";
		} else {
			return "https://app.sandbox.midtrans.com/snap/snap.js";
		}
	}

	/**
	 * Fetch transaction data from Payment Gateway
	 * base on payment geteway's ID
	 *
	 * @param string $paymentGatewayOrderId
	 * @return mixed[]
	 */
	public function getPaymentGatewayData($paymentGatewayOrderId) {
		// get notification from veritrans
		return \Midtrans\Transaction::status($paymentGatewayOrderId);
	}

	/**
	 * Save MidtransTransaction from JSON data
	 * create a new record if MidtransTransaction is not exists
	 *
	 * @param Object $transactionData
	 * @return updated/created MidtransTransaction
	 */
	public function savePaymentGatewayTransaction($transactionData)
	{
		// get orderId
		$orderId = $this
			->getOrderIdFromPaymentGatewayOrderId($transactionData->order_id);

		$json = json_encode($transactionData);

		// get first midtransTransaction
		$midtransTransaction = MidtransTransaction::
			where('order_id', $orderId)
			->first();

		// or create a new object MidtransTransaction
		if (is_null($midtransTransaction)) {
			$midtransTransaction = new MidtransTransaction;
		}

		$midtransTransaction->raw_json = $json;
		$midtransTransaction->order_id = $orderId;
		$midtransTransaction->order_number = $transactionData->order_id;
		$midtransTransaction->bank = (
			$transactionData->acquiring_bank
			?? $transactionData->bank
			?? $transactionData->va_numbers[0]->bank
			?? null
		);
		$midtransTransaction->payment_type = $transactionData->payment_type;
		$midtransTransaction->status_code = $transactionData->status_code ?? null;
		$midtransTransaction->save();

		return $midtransTransaction;
	}

	/**
	 * Return string url to download instruction
	 *
	 * @param \App\Models\Order $order
	 * @return string url
	 */
	public function getInstructionPdfUrl(Order $order)
	{
		return (
			\Midtrans\Config::getSnapBaseUrl().
			'/transactions/'.
			$order->snap_transaction_token.
			'/pdf'
		);
	}

}
