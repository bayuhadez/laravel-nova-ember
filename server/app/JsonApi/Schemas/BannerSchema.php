<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class BannerSchema extends SchemaProvider
{

	/**
	 * @var string
	 */
	protected $resourceType = 'banners';

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
			'image' => $resource->imageUrl,
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
			'product' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['product']),
				self::DATA => function () use ($resource) {
					return $resource->product;
				},
			]
		];
	}
}
