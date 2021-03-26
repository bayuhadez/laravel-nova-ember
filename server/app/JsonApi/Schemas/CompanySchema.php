<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class CompanySchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'companies';

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
            'name'                   => $resource->name,
            'division'               => $resource->division,
            'code'                   => $resource->code,
            'telephone-number'       => $resource->telephone_number,
            'mobile-number'          => $resource->mobile_number,
            'fax-number'             => $resource->fax_number,
            'value-added-tax-number' => $resource->value_added_tax_number,
            'value-added-tax-type'   => $resource->value_added_tax_type,
            'created-at'             => $resource->created_at->toAtomString(),
            'updated-at'             => $resource->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'banners' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['banners']),
                self::DATA => function () use ($resource) {
                    return $resource->banners;
                },
            ],
            'children-company' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['children-company']),
                self::DATA => function () use ($resource) {
                    return $resource->childrenCompany;
                },
            ],
            'company-addresses' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['company-addresses']),
                self::DATA => function () use ($resource) {
                    return $resource->companyAddresses;
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
            'company-customers' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['company-customers']),
                self::DATA => function () use ($resource) {
                    return $resource->companyCustomers;
                },
            ],
            'customers' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['customers']),
                self::DATA => function () use ($resource) {
                    return $resource->customers;
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
            'parent-company' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['parent-company']),
                self::DATA => function () use ($resource) {
                    return $resource->parentCompany;
                },
            ],
            'products' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['products']),
                self::DATA => function () use ($resource) {
                    return $resource->products;
                },
            ],
            'product-categories' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-categories']),
                self::DATA => function () use ($resource) {
                    return $resource->productCategories;
                },
            ],
            'services' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['services']),
                self::DATA => function () use ($resource) {
                    return $resource->services;
                },
            ],
            'staffs' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['staffs']),
                self::DATA => function () use ($resource) {
                    return $resource->staffs;
                },
            ],
            'stock-divisions' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['stock-divisions']),
                self::DATA => function () use ($resource) {
                    return $resource->stockDivisions;
                },
            ],
            'updated-by' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['updated-by']),
                self::DATA => function () use ($resource) {
                    return $resource->updatedBy;
                },
            ],
            'warehouses' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['warehouses']),
                self::DATA => function () use ($resource) {
                    return $resource->warehouses;
                },
            ],
        ];
    }
}
