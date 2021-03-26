<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class CountrySchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'countries';

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
            'iso' => $resource->iso,
            'name' => $resource->nicename,
        ];
    }

    public function getResourceLinks($resource)
    {
        return [];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'addresses' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['addresses']),
                self::DATA => function () use ($resource) {
                    return $resource->addresses;
                },
            ]
        ];
    }
}
