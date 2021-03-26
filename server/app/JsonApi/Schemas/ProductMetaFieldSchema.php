<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class ProductMetaFieldSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'product-meta-fields';

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
            'label' => $resource->label,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
	{
        return [
            'product-meta-field-group' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-meta-field-group']),
                self::DATA => function () use ($resource) {
                    return $resource->productMetaFieldGroup;
                },
            ],
            'product-meta-values' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-meta-values']),
                self::DATA => function () use ($resource) {
                    return $resource->productMetaValues;
                },
            ],
        ];
    }
}
