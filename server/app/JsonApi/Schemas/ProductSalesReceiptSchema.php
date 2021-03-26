<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class ProductSalesReceiptSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'product-sales-receipts';

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
            'product' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product']),
                self::DATA => function () use ($resource) {
                    return $resource->product;
                },
            ],
            'product-sales-order' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-sales-order']),
                self::DATA => function () use ($resource) {
                    return $resource->productSalesOrder;
                },
            ],
            'sales-receipt' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['sales-receipt']),
                self::DATA => function () use ($resource) {
                    return $resource->salesReceipt;
                },
            ],
        ];
    }

}
