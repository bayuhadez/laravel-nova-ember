<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class CompanyWarehouseSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'company-warehouses';

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
        return [];
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
            'warehouse' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['warehouse']),
                self::DATA => function () use ($resource) {
                    return $resource->warehouse;
                },
            ]
        ];
    }
}
