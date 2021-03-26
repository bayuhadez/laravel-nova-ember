<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class RackSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'racks';

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
            'code'        => $resource->code,
            'name'        => $resource->name,
            'description' => $resource->description,
            'quantity'    => $resource->quantity,
            'created-at'  => $resource->created_at->toAtomString(),
            'updated-at'  => $resource->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'warehouse' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['warehouse']),
                self::DATA => function () use ($resource) {
                    return $resource->warehouse;
                },
            ],
            'product-stocks' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-stocks']),
                self::DATA => function () use ($resource) {
                    return $resource->productStocks;
                },
            ]
        ];
    }
}
