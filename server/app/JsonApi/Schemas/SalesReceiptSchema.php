<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class SalesReceiptSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'sales-receipts';

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
            'is-ppn' => $resource->is_ppn,
            'total' => $resource->total,
            'sub-total' => $resource->sub_total,
            'due-at' => $resource->due_at,
            'receipted-at' => $resource->receipted_at,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
            'sales-names' => $resource->salesNames,
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
            'address' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['address']),
                self::DATA => function () use ($resource) {
                    return $resource->address;
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
