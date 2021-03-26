<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class SponsorSchema extends SchemaProvider
{

	/**
	 * @var string
	 */
	protected $resourceType = 'sponsors';

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
            'platinum' => $resource->platinum
		];
    }

}
