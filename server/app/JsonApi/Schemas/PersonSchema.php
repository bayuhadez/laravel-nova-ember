<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class PersonSchema extends SchemaProvider
{

	/**
	 * @var string
	 */
	protected $resourceType = 'people';

	/**
	 * @param $resource
	 *      the domain record being serialized.
	 * @return string
	 */
	public function getId($resource)
	{
		return (string) $resource->getRouteKey();
	}

	/**
	 * @param $resource
	 *      the domain record being serialized.
	 * @return array
	 */
	public function getAttributes($resource)
	{
		return [
			'first-name' => $resource->first_name,
			'last-name' => $resource->last_name,
			'telephone-number' => $resource->telephone_number,
			'mobile-number' => $resource->mobile_number,
			'fax-number' => $resource->fax_number,
			'created-at' => $resource->created_at->toAtomString(),
			'updated-at' => $resource->updated_at->toAtomString(),
		];
	}

	public function getRelationships($resource, $isPrimary, array $includeRelationships)
	{
		return [
            'person-addresses' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['person-addresses']),
				self::DATA => function () use ($resource) {
					return $resource->personAddresses;
				},
			],
			'regency' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['regency']),
				self::DATA => function () use ($resource) {
					return $resource->regency;
				},
			],
            'staffs' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['staffs']),
				self::DATA => function () use ($resource) {
					return $resource->staffs;
				},
			],
            'user' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['user']),
				self::DATA => function () use ($resource) {
					return $resource->user;
				},
			],
		];
	}
}
