<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class PurchaseReceiptSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'purchase-receipts';

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
            'pre-order-number' => $resource->pre_order_number,
            'son-number' => $resource->son_number,
            'receipted-at' => $resource->receipted_at,
            'due-at' => $resource->due_at,
            'currency-rate' => $resource->currency_rate,
            'is-ppn' => $resource->is_ppn,
            'rounding-type' => $resource->rounding_type,
            'rounding-value' => $resource->rounding_value,
            'total' => $resource->total,
            'sub-total' => $resource->sub_total,
            'discounts' => $resource->discounts,
            'delivery-order-number' => $resource->delivery_order_number,
            'tax-invoice-number' => $resource->tax_invoice_number,
            'delivery-order-at' => $resource->delivery_order_at,
            'receipt-letter-at' => $resource->receipt_letter_at,
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
                self::SHOW_DATA => isset($includeRelationships['company']),
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
            'currency' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['currency']),
                self::DATA => function () use ($resource) {
                    return $resource->currency;
                },
            ],
            'purchase-receipt-products' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['pre-order-products']),
                self::DATA => function () use ($resource) {
                    return $resource->purchaseReceiptProducts;
                },
            ],
            'pre-orders' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['pre-orders']),
                self::DATA => function () use ($resource) {
                    return $resource->preOrders;
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
        ];
    }
}
