<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class ProductSalesOrderSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'product-sales-orders';

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
            'discount' => $resource->discount,
            'order-quantity' => $resource->order_quantity,
            'sell-price' => $resource->sell_price,
            'sub-total' => $resource->sub_total,
            'status' => $resource->status,
            'amount-approved' => $resource->amount_approved,
            'amount-rejected' => $resource->amount_rejected,
            'amount-prepared' => $resource->amount_prepared,
            'amount-returned' => $resource->amount_returned,
            'amount-left-to-prepare' => $resource->amountLeftToPrepare,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
            'status-text' => $resource->statusText,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'stock-division' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['stock-division']),
                self::DATA => function () use ($resource) {
                    return $resource->stockDivision;
                },
            ],
            'sales-order' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['sales-order']),
                self::DATA => function () use ($resource) {
                    return $resource->salesOrder;
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
            'product-transaction-receipt' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-transaction-receipt']),
                self::DATA => function () use ($resource) {
                    return $resource->productTransactionReceipt;
                },
            ],
            'product-stock-movements' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-stock-movements']),
                self::DATA => function () use ($resource) {
                    return $resource->productStockMovements;
                },
            ],
        ];
    }

}
