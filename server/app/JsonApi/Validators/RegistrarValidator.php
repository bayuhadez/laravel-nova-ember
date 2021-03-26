<?php

namespace App\JsonApi\Validators;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class RegistrarValidator extends AbstractValidators
{

	/**
	 * The include paths a client is allowed to request.
	 *
	 * @var string[]|null
	 *      the allowed paths, an empty array for none allowed, or null to allow all paths.
	 */
	protected $allowedIncludePaths = [];

	/**
	 * The sort field names a client is allowed send.
	 *
	 * @var string[]|null
	 *      the allowed fields, an empty array for none allowed, or null to allow all fields.
	 */
	protected $allowedSortParameters = [];

	protected $attributes = [
		'password-confirmation' => 'password confirmation',
		'first-name' => 'first name',
		'last-name' => 'last name',
	];

	/**
	 * Get resource validation rules.
	 *
	 * @param mixed|null $record
	 *      the record being updated, or null if creating a resource.
	 * @return mixed
	 */
	protected function rules($record = null): array
	{
		return [
			'address' => 'required|max:192',
			'email' => 'required|email|unique:users,email|max:191',
			'first-name' => 'required|max:32',
			'last-name' => 'required|max:32',
			'password' => 'required|min:3',
			'password-confirmation' => 'required|same:password',
			'phone' => 'required|max:16',
			'registration-certificate-number' => 'required|min:8|max:20',
		];
	}

	/**
	 * Get query parameter validation rules.
	 *
	 * @return array
	 */
	protected function queryRules(): array
	{
		return [
			//
		];
	}

}
