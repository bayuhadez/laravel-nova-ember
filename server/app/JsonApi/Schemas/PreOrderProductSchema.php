<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class PreOrderProductSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'pre-order-products';

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
            'quantity' => $resource->quantity,
            'purchase-price' => $resource->purchase_price,
            'purchase-price-per-pcs' => $resource->purchase_price_per_pcs,
            'purchase-price-foreign' => $resource->purchase_price_foreign,
            'purchase-price-foreign-per-pcs' => $resource->purchase_price_foreign_per_pcs,
            'discounts' => $resource->discounts,
            'sub-total' => $resource->sub_total,
            'cost' => $resource->cost,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'pre-order' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['pre-order']),
                self::DATA => function () use ($resource) {
                    return $resource->preOrder;
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
            'product-unit' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-unit']),
                self::DATA => function () use ($resource) {
                    return $resource->productUnit;
                },
            ],
            'unit' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['unit']),
                self::DATA => function () use ($resource) {
                    return $resource->unit;
                },
            ],
        ];
    }
}
