<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class ProductSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'products';

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
            'barcode' => $resource->barcode,
            'image' => $resource->imageUrl,
            'thumbnail-image' => $resource->thumbnailUrl,
            'name' => $resource->name,
            'description' => $resource->description,
            'price' => $resource->price,

            // show base price if allowed
            'base-price' => $resource->base_price,

            'status' => $resource->status,
            'stock' => $resource->stock,
            'maximum-stock' => $resource->maximum_stock,
            'minimum-stock' => $resource->minimum_stock,
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
            'product-categories' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-categories']),
                self::DATA => function () use ($resource) {
                    return $resource->productCategories;
                },
            ],
            'product-banner' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-banner']),
                self::DATA => function () use ($resource) {
                    return $resource->productBanner;
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

            'company-products' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['company-products']),
                self::DATA => function () use ($resource) {
                    return $resource->companyProducts;
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

            'seminar-product-meta' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['seminar-product-meta']),
                self::DATA => function () use ($resource) {
                    return $resource->seminarProductMeta;
                },
            ],

            'chat-rooms' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['chat-rooms']),
                self::DATA => function () use ($resource) {
                    return $resource->chatRooms;
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

            'product-units' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['product-units']),
                self::DATA => function () use ($resource) {
                    return $resource->productUnits;
                },
            ],

            'units' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['units']),
                self::DATA => function () use ($resource) {
                    return $resource->units;
                },
            ],
        ];
    }

}
