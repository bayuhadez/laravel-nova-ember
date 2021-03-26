<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class ProductTransactionReceiptSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'product-transaction-receipts';

    /**
     * @param \App\ProductTransactionReceipt $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\ProductTransactionReceipt $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'quantity' => $resource->quantity,
            'price' => $resource->price,
            'price-per-pcs' => $resource->price_per_pcs,
            'foreign-price' => $resource->foreign_price,
            'foreign-price-per-pcs' => $resource->foreign_price_per_pcs,
            'discounts' => $resource->discounts,
            'sub-total' => $resource->sub_total,
            'cost' => $resource->cost,
            'total' => $resource->total,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }

    /**
     * @inheritDoc
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
            'product-unit' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-unit']),
                self::DATA => function () use ($resource) {
                    return $resource->productUnit;
                },
            ],
            'transaction-receipt' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['transaction-receipt']),
                self::DATA => function () use ($resource) {
                    return $resource->transactionReceipt;
                },
            ],
            'pre-order-product' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['pre-order-product']),
                self::DATA => function () use ($resource) {
                    return $resource->preOrderProduct;
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
