<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class RequestOrderSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'request-orders';

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
            'number' => $resource->number,
            'memo' => $resource->memo,
            'status' => $resource->status,
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
                self::SHOW_DATA => true,
                self::DATA => function () use ($resource) {
                    return $resource->company;
                },
            ],
            'created-by' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['created-by']),
                self::DATA => function () use ($resource) {
                    return $resource->createdBy;
                },
            ],
            'pre-order' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['pre-order']),
                self::DATA => function () use ($resource) {
                    return $resource->preOrder;
                },
            ],
            'staff' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['staff']),
                self::DATA => function () use ($resource) {
                    return $resource->staff;
                },
            ],
            'staff-position' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['staff-position']),
                self::DATA => function () use ($resource) {
                    return $resource->staffPosition;
                },
            ],
            'request-order-products' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['request-order-products']),
                self::DATA => function () use ($resource) {
                    return $resource->requestOrderProducts;
                },
            ],
        ];
    }
}
