<?php

namespace App\Http\Requests;

use App\Interfaces\ApprovableInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLicenseRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'full-name' => 'required|max:191',
			'expiry-date' => 'required|max:10|date_format:"d/m/Y"',
			'photo' => 'required|image|max:3072',
			'number' => [
				'required',
				'max:48',
				Rule::unique('licenses')->where(function ($query) {
					return $query->where('status', ApprovableInterface::STATUS_APPROVED);
				}),
			],
		];
	}

	public function attributes()
	{
		return [
			'expiry-date' => 'Expiry Date',
			'full-name' => 'Full Name',
			'number' => 'Number',
		];
	}

	/**
	 * Set custom messages for validator errors.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'expiry-date.date_format' => 'The :attribute does not match the format dd/mm/yyyy',
		];
	}
}
