<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class ChatSchema extends SchemaProvider
{

	/**
	 * @var string
	 */
	protected $resourceType = 'chats';

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
			'message' => $resource->message,
			'sender-name' => $resource->senderName,
			'created-at' => $resource->created_at->toAtomString(),
			'updated-at' => $resource->updated_at->toAtomString(),
		];
	}
}
