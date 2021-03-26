<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class TransactionReceiptSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'transaction-receipts';

    /**
     * @param \App\TransactionReceipt $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\TransactionReceipt $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
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
            'supplier' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['supplier']),
                self::DATA => function () use ($resource) {
                    return $resource->supplier;
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
            'product-transaction-receipts' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-transaction-receipts']),
                self::DATA => function () use ($resource) {
                    return $resource->productTransactionReceipts;
                },
            ],
            'service-transaction-receipts' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['service-transaction-receipts']),
                self::DATA => function () use ($resource) {
                    return $resource->serviceTransactionReceipts;
                },
            ],
        ];
    }
}
