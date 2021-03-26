<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class PurchaseReceiptProductSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'purchase-receipt-products';

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
            'price' => $resource->price,
            'discount' => $resource->discount,
            'sub-total' => $resource->sub_total,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }
}
