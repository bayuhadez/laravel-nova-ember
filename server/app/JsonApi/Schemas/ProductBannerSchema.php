<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class ProductBannerSchema extends SchemaProvider
{
	/**
	 * @var string
	 */
	protected $resourceType = 'product-banners';

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
			'banner-name' => $resource->banner_name,
			'banner-image-path' => $resource->imageUrl,
		];
	}

	public function getRelationships($resource, $isPrimary, array $includeRelationships)
	{
		return [
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
