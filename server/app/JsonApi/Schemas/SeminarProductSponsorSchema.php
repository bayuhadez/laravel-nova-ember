<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class SeminarProductSponsorSchema extends SchemaProvider
{

	/**
	 * @var string
	 */
	protected $resourceType = 'seminar-product-sponsors';

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
			'sponsor-name' => $resource->sponsor_name,
			'sponsor-image-path' => $resource->sponsor_image_path,
		];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
	{
		return [
			'seminar-product-meta' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['seminar-product-meta']),
				self::DATA => function () use ($resource) {
					return $resource->seminarProductMeta;
				},
			]
		];
	}
}
