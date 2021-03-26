<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class ProductStockMovementSchema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'product-stock-movements';

    /**
     * @param \App\ProductStockMovement $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\ProductStockMovement $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'datetime' => $resource->datetime->toAtomString(),
            'in-or-out' => $resource->in_or_out,
            'quantity' => $resource->quantity,
            'stock' => $resource->stock,
            'price' => $resource->price,
            'receipt-number' => $resource->receiptNumber,
            'from' => $resource->from,
            'to' => $resource->to,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'customer' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['customer']),
                self::DATA => function () use ($resource) {
                    return $resource->customer;
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
            'product-stock-movements' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-stock-movements']),
                self::DATA => function () use ($resource) {
                    return $resource->productStockMovements;
                },
            ],
            'rack' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['rack']),
                self::DATA => function () use ($resource) {
                    return $resource->rack;
                },
            ],
            'stock-division' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['stock-division']),
                self::DATA => function () use ($resource) {
                    return $resource->stockDivision;
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
            'product-stock' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-stock']),
                self::DATA => function () use ($resource) {
                    return $resource->productStock;
                },
            ],
            'user' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['user']),
                self::DATA => function () use ($resource) {
                    return $resource->user;
                },
            ],
            'purchase-receipt' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['purchase-receipt']),
                self::DATA => function () use ($resource) {
                    return $resource->purchaseReceipt;
                },
            ],
            'sales-receipt' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['sales-receipt']),
                self::DATA => function () use ($resource) {
                    return $resource->salesReceipt;
                },
            ]
        ];
    }
}
