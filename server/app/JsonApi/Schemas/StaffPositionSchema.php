<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class StaffPositionSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'staff-positions';

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
            'name' => $resource->name,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }
    
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'staffs' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['staffs']),
                self::DATA => function () use ($resource) {
                    return $resource->staffs;
                },
            ]
        ];
    }
}
