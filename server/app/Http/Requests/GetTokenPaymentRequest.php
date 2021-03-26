<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class GetTokenPaymentRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
		/*
		$user = auth()->user();
		$orderId = $this->input('orderId');
		$order = Order::find($orderId);

		if (!is_null($order) && $user->can('getTokenPayment', $order)) {
			return true;
		}

		return false;
		 */
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'orderId' => 'required',
		];
	}
}
