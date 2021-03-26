<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class SalesOrderSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'sales-orders';

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
            'description' => $resource->description,
            'discount' => $resource->discount,
            'status' => $resource->status,
            'total' => $resource->total,
            'grand-total' => $resource->grand_total,
            'requires-delivery' => $resource->requires_delivery,
            'ordered-at' => $resource->ordered_at ? $resource->ordered_at->toAtomString() : null,
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
            'customer' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['customer']),
                self::DATA => function () use ($resource) {
                    return $resource->customer;
                },
            ],
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
            'warehouse-staff' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['warehouse-staff']),
                self::DATA => function () use ($resource) {
                    return $resource->warehouseStaff;
                },
            ],
            'sales' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['sales']),
                self::DATA => function () use ($resource) {
                    return $resource->sales;
                },
            ],
            'product-sales-orders' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-sales-orders']),
                self::DATA => function () use ($resource) {
                    return $resource->productSalesOrders;
                },
            ],
            'delivery-recipient-customer' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['delivery-recipient-customer']),
                self::DATA => function () use ($resource) {
                    return $resource->deliveryRecipientCustomer;
                },
            ],
            'delivery-address' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['delivery-address']),
                self::DATA => function () use ($resource) {
                    return $resource->deliveryAddress;
                },
            ],
        ];
    }

}
