<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class RequestOrderProductSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'request-order-products';

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
            'sorting-number' => $resource->sorting_number,
            'quantity' => $resource->quantity,
            'information' => $resource->information,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'request-order' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['request-order']),
                self::DATA => function () use ($resource) {
                    return $resource->requestOrder;
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
            'product-unit' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-unit']),
                self::DATA => function () use ($resource) {
                    return $resource->productUnit;
                },
            ],
            'unit' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['unit']),
                self::DATA => function () use ($resource) {
                    return $resource->unit;
                },
            ],
        ];
    }
}
