<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class WarehouseSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'warehouses';

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
            'name' => $resource->name,
            'address' => $resource->address,
            'phone' => $resource->phone,
            'person-in-charge' => $resource->person_in_charge,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'companies' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['companies']),
                self::DATA => function () use ($resource) {
                    return $resource->companies;
                },
            ],
            'company-warehouses' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['company-warehouses']),
                self::DATA => function () use ($resource) {
                    return $resource->companyWarehouses;
                },
            ],
            'warehouse-categories' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['warehouseCategories']),
                self::DATA => function () use ($resource) {
                    return $resource->warehouseCategories;
                },
            ],
            'racks' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['racks']),
                self::DATA => function () use ($resource) {
                    return $resource->racks;
                },
            ]
        ];
    }
}
