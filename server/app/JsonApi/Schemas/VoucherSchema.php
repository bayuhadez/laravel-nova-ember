<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class VoucherSchema extends SchemaProvider
{

	/**
	 * @var string
	 */
	protected $resourceType = 'vouchers';

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
			'name' => $resource->name,
			'stock' => $resource->stock,
			'amount' => $resource->amount,
			'created-at' => $resource->created_at->toAtomString(),
			'updated-at' => $resource->updated_at->toAtomString(),
		];
	}

	public function getRelationships($resource, $isPrimary, array $includeRelationships)
	{
		return [
			'company' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['company']),
				self::DATA => function () use ($resource) {
					return $resource->company;
				},
			],
			'orders' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['orders']),
				self::DATA => function () use ($resource) {
					return $resource->orders;
				},
			]
		];
	}
}
