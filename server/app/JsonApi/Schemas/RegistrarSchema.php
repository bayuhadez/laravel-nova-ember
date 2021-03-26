<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class RegistrarSchema extends SchemaProvider
{
	/**
	 * @var string
	 */
	protected $resourceType = 'registrars';

	/**
	 * @param $resource
	 *      the domain record being serialized.
	 * @return string
	 */
	public function getId($resource)
	{
		// TODO
	}

	/**
	 * @param $resource
	 *      the domain record being serialized.
	 * @return array
	 */
	public function getAttributes($resource)
	{
		return [
			'name' => $resource->email,
			'address' => $resource->address,
			'first-name' => $resource->first_name,
			'last-name' => $resource->last_name,
			'phone' => $resource->phone,
			'registration-certificate-number' => $resource->registration_certificate_number,
			//'token' => $resource->token,
		];
	}

}
