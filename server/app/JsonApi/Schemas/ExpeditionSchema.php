<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class ExpeditionSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'expeditions';

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
            'fax-number' => $resource->fax_number,
            'mobile-number' => $resource->mobile_number,
            'telephone-number' => $resource->telephone_number,
            'pic' => $resource->pic,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'expedition-products' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['expedition-products']),
                self::DATA => function () use ($resource) {
                    return $resource->expeditionProducts;
                },
            ],
            'province' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['province']),
                self::DATA => function () use ($resource) {
                    return $resource->province;
                },
            ],
            'regency' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['regency']),
                self::DATA => function () use ($resource) {
                    return $resource->regency;
                },
            ],
            'expedition-category' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['expedition-category']),
                self::DATA => function () use ($resource) {
                    return $resource->expeditionCategory;
                },
            ],
            'currency' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['currency']),
                self::DATA => function () use ($resource) {
                    return $resource->currency;
                },
            ],
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
