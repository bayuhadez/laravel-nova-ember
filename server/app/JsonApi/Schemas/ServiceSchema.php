<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class ServiceSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'services';

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
            'description' => $resource->description,
            'service-category-id' => $resource->service_category_id,
            'price' => $resource->price,
            'point' => $resource->point,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'service-category' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['service-category']),
                self::DATA => function () use ($resource) {
                    return $resource->serviceCategory;
                },
            ],
            'company-services' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['company-services']),
                self::DATA => function () use ($resource) {
                    return $resource->companyServices;
                },
            ],
        ];
    }
}
