<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class CompanyProductSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'company-products';

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
            'point' => $resource->point,
            'price' => $resource->price,
            'receipt-price' => $resource->receiptPrice,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
	{
		return [
			'product' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['product']),
				self::DATA => function () use ($resource) {
					return $resource->product;
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
		];
	}
}
