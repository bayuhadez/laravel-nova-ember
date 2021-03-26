<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class OrderSchema extends SchemaProvider
{

	/**
	 * @var string
	 */
	protected $resourceType = 'orders';

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
			'gross-amount' => $resource->gross_amount,
			'payment-id' => $resource->paymentId,
			'status' => $resource->status,
			'payment-status' => $resource->payment_status,
			'created-at' => $resource->created_at->toAtomString(),
			'updated-at' => $resource->updated_at->toAtomString(),
		];
	}

	public function getRelationships($resource, $isPrimary, array $includeRelationships)
	{
		return [
			'order-details' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['order-details']),
				self::DATA => function () use ($resource) {
					return $resource->orderDetails;
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

			'user' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['user']),
				self::DATA => function () use ($resource) {
					return $resource->user;
				},
			],

			'voucher' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['voucher']),
				self::DATA => function () use ($resource) {
					return $resource->voucher;
				},
			],

			'transaction' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['transaction']),
				self::DATA => function () use ($resource) {
					return $resource->midtransTransaction;
				},
			]
		];
	}

}
