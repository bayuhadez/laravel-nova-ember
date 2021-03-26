<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class SalesOrderServiceSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'sales-order-services';

    /**
     * @param \App\SalesOrderService $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\SalesOrderService $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'discount' => $resource->discount,
            'order-quantity' => $resource->order_quantity,
            'sell-price' => $resource->sell_price,
            'total' => $resource->total,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'sales-order' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['sales-order']),
                self::DATA => function () use ($resource) {
                    return $resource->salesOrder;
                },
            ],
            'service' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['service']),
                self::DATA => function () use ($resource) {
                    return $resource->service;
                },
            ],
            'service-transaction-receipt' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['service-transaction-receipt']),
                self::DATA => function () use ($resource) {
                    return $resource->serviceTransactionReceipt;
                },
            ],
        ];
    }
}
