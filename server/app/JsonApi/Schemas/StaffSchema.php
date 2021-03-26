<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class StaffSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'staffs';

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
            'company-customers' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['company-customers']),
                self::DATA => function () use ($resource) {
                    return $resource->companyCustomers;
                },
            ],
            'person' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['person']),
                self::DATA => function () use ($resource) {
                    return $resource->person;
                },
            ],
            'staff-categories' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['staff-categories']),
                self::DATA => function () use ($resource) {
                    return $resource->staffCategories;
                },
            ],
            'staff-positions' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['staff-positions']),
                self::DATA => function () use ($resource) {
                    return $resource->staffPositions;
                },
            ],
        ];
    }
}
