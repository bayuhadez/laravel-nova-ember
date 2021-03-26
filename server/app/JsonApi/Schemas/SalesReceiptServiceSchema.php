<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class SalesReceiptServiceSchema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'sales-receipt-services';

    /**
     * @param \App\SalesReceiptService $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\SalesReceiptService $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
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
            'sales-receipt' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['sales-receipt']),
                self::DATA => function () use ($resource) {
                    return $resource->salesReceipt;
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
            'sales-order-service' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['sales-order-service']),
                self::DATA => function () use ($resource) {
                    return $resource->salesOrderService;
                },
            ],
        ];
    }
}
