<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class SupplierSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'suppliers';
    
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
            'code' => $resource->code,
            'pic' => $resource->pic,
            'top' => $resource->top,
            'plafon' => $resource->plafon,
            'accounting-number' => $resource->accounting_number,
            'information' => $resource->information,
            'fax-number' => $resource->company->fax_number ?? null,
            'mobile-number' => $resource->company->mobile_number ?? null,
            'telephone-number' => $resource->company->telephone_number ?? null,
            'address' => $resource->company->defaultOrFirstAddress->address ?? null,
            'regency' => $resource->company->defaultOrFirstAddress->regency->name ?? null,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'currency' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['currency']),
                self::DATA => function () use ($resource) {
                    return $resource->currency;
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
            'pic' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['pic']),
                self::DATA => function () use ($resource) {
                    return $resource->pic;
                },
            ],
            'supplier-category' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['supplier-category']),
                self::DATA => function () use ($resource) {
                    return $resource->supplierCategory;
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
        ];
    }

}
