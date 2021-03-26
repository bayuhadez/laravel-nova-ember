<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class PhoneSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'phones';

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
            'country-id' => $resource->country_id,
            'number' => $resource->number,
            'phonecode' => $resource->phonecode,
            'phonecode-and-number' => $resource->phonecodeAndNumber,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'country' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['country']),
                self::DATA => function () use ($resource) {
                    return $resource->country;
                },
            ],
        ];
    }
}
