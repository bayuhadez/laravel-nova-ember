<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class OrderDetailSchema extends SchemaProvider
{

	/**
	 * @var string
	 */
	protected $resourceType = 'order-details';

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
			'brand' => $resource->brand,
			'category' => $resource->category,
			'merchant_name' => $resource->merchant_name,
			'name' => $resource->name,
			'price' => $resource->price,
			'quantity' => $resource->quantity,
			'created-at' => $resource->created_at->toAtomString(),
			'updated-at' => $resource->updated_at->toAtomString(),
		];
	}

	public function getRelationships($resource, $isPrimary, array $includeRelationships)
	{
		return [
			'order' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['order']),
				self::DATA => function () use ($resource) {
					return $resource->order;
				},
			],

			'product' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['product']),
				self::DATA => function () use ($resource) {
					return $resource->product;
				},
			],
		];
	}
}
