<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class ChatRoomSchema extends SchemaProvider
{

	/**
	 * @var string
	 */
	protected $resourceType = 'chat-rooms';

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
			'created-at' => $resource->created_at->toAtomString(),
			'updated-at' => $resource->updated_at->toAtomString(),
		];
	}

	public function getRelationships($resource, $isPrimary, array $includeRelationships)
	{
		return [
			'chats' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['chats']),
				self::DATA => function () use ($resource) {
					return $resource->chats;
				},
			],
		];
	}
}
