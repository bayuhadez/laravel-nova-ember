<?php
namespace App\Http\Controllers;

use App\Midtrans\Veritrans\Midtrans;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class SnapMidtransController extends Controller
{
	private $midtransService;

	public function __construct(MidtransService $midtransService)
	{
		$this->midtransService = $midtransService;
	}

	public function notification()
	{
		$json_result = file_get_contents('php://input');

		$result = json_decode($json_result);

		if ($result) {

			$paymentGatewayOrderId = $result->order_id;

			$order = $this
				->midtransService
				->updateOrderBasedOnPaymentGatewayOrder($paymentGatewayOrderId);

		}
	}

	public function snap()
	{
		return view('midtrans.snap_checkout');
	}

	/**
	 * @example
	 */
	public function token()
	{
		$order = new Order;
		$order->gross_amount = 400000;
		$order->company_id = 1;
		$order->user_id = 5;
		$order->save();

		try {
			$token = $this->midtransService->getSnapToken($order);

			//return redirect($vtweb_url);
			echo $token;

		} catch (Exception $e) {
			return $e->getMessage;
		}
	}

	/**
	 * @example
	 */
	public function finish(Request $request)
	{
		$result = $request->input('result_data');
		$result = json_decode($result);
		echo $result->status_message . '<br>';
		echo 'RESULT <br><pre>';
		var_dump($result);
		echo '</pre>' ;
	}

}
