<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Response;
use Illuminate\Http\Request;
use App\Services\MidtransService;
use App\Services\OrderService;
use App\Models\Product;

class OrderController extends Controller
{
	public function __construct(MidtransService $midtransService)
	{
		$this->midtransService = $midtransService;
	}

	public function create(Request $request)
	{
		$user = auth('api')->user();

		$products = Product::whereIn('id', $request->get('productIds'))->get();

		$order = OrderService::createOrderByProductsForUser($products, $user);

		$token = $this->midtransService->getSnapToken($order);

		return $token;
	}
}
