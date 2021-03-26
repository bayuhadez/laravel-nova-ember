<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CreateOrderWithOrderDetailsRequest;
use App\Http\Requests\GetTokenPaymentRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use App\Services\MidtransService;
use App\Services\OrderService;
use CloudCreativity\LaravelJsonApi\Document\Error;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
use Illuminate\Http\Request;
use CloudCreativity\LaravelJsonApi\Http\Requests\FetchResource;

class OrdersController extends JsonApiController
{
	private $midtransService;

	public function __construct(MidtransService $midtransService)
	{
		$this->midtransService = $midtransService;
	}

	/*
	 * @deprecated
	public function created($order)
	{
		try {

			$token = $this->midtransService->getSnapToken($order);

			$order->snap_transaction_token = $token;

			$order->save();

			return $this->reply()->content($order);

		} catch (Exception $e) {

			return $this->reply()->errors(Error::create([
				'title' => 'Snap Token',
				'detail' => $e->getMessage,
				'status' => '402',
			]));

		}
	}
	 */

	/* ----------------------------------------- */
	/* Custom Actions -------------------------- */
	/* ----------------------------------------- */

	/**
	 * Create Order and OrderDetails
	 * @param \Illuminate\Http\Request $request
	 * @return Json Order resource (jsonapi format)
	 */
	public function createWithOrderDetails(CreateOrderWithOrderDetailsRequest $request)
	{
		$user = auth('api')->user();

		$products = Product::whereIn('id', $request->get('productIds'))->get();
		$voucher = Voucher::where('id', $request->get('voucherId'))->first();

		try {

			$order = OrderService::createOrderByProductsForUser($products, $user, $voucher);

			return $this->reply()->content($order);

		} catch (Exception $e) {

			return $this->reply()->errors(Error::create([
				'title' => 'Snap Token',
				'detail' => $e->getMessage,
				'status' => '402',
			]));
		}
	}

	/**
	 * Get snap token or generate it if it's not exist
	 *
	 * @param \App\Http\Requests\GetTokenPaymentRequest $request
	 * @return string|null Snap Token
	 */
	public function getTokenPayment(GetTokenPaymentRequest $request)
	{
		$user = auth('api')->user();

		$order = Order::find($request->get('orderId'));

		$token = null;

		// create token if it's empty
		if (empty($token->snap_transaction_token)) {

			$token = $this->midtransService->getSnapToken($order);

			$order->snap_transaction_token = $token;

			$order->save();
		}

		return $token;
	}

}
