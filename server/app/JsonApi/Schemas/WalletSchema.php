<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class WalletSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'wallets';

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
            'code'          => $resource->code,
            'name'          => $resource->name,
            'payment-type'  => $resource->payment_type,
            'amount'        => $resource->amount,
            'created-at'    => $resource->created_at->toAtomString(),
            'updated-at'    => $resource->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'payment-methods' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['payment-methods']),
                self::DATA => function () use ($resource) {
                    return $resource->paymentMethods;
                },
            ],
            'companies' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['companies']),
                self::DATA => function () use ($resource) {
                    return $resource->companies;
                },
            ]
        ];
    }
}
