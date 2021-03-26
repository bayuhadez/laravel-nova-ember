<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class PreOrderSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'pre-orders';

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
            'son-number' => $resource->son_number,
            'ordered-at' => $resource->ordered_at->toAtomString(),
            'due-at' => !empty($resource->due_at) ? $resource->due_at->toAtomString() : null,
            'status' => $resource->status,
            'currency-rate' => $resource->currency_rate,
            'is-ppn' => $resource->is_ppn,
            'rounding-type' => $resource->rounding_type,
            'rounding-value' => $resource->rounding_value,
            'total' => $resource->total,
            'total-item' => $resource->total_item,
            'total-quantity' => $resource->total_quantity,
            'rounded-total' => $resource->rounded_total,
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
            'supplier' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['supplier']),
                self::DATA => function () use ($resource) {
                    return $resource->supplier;
                },
            ],
            'pre-order-products' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['pre-order-products']),
                self::DATA => function () use ($resource) {
                    return $resource->preOrderProducts;
                },
            ],
            'request-orders' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['request-orders']),
                self::DATA => function () use ($resource) {
                    return $resource->requestOrders;
                },
            ],
        ];
    }
}
