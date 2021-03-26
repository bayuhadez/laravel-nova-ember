<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderWithOrderDetailsRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$canBuyProducts = true;

		// make sure product is available to be bought
		$productIds = $this->input('productIds');
		$products = Product::whereIn('id', $productIds);

		foreach ($products as $product) {

			$canBuyProducts = $use->can('buy', $product);

			if (!$canBuyProducts) {
				break;
			}
		}

		return ($canBuyProducts);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'productIds' => 'required',
		];
	}

	public function attributes()
	{
		return [];
	}

	/**
	 * Set custom messages for validator errors.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [];
	}
}

