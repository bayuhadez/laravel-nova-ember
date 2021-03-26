<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class SeminarProductMetaSchema extends SchemaProvider
{

	/**
	 * @var string
	 */
	protected $resourceType = 'seminar-product-metas';

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
			'is-session-in-progress' => $resource->is_session_in_progress,
			'stream-key' => $resource->stream_key,
			'start-time' => (
				!empty($resource->start_time)
				? $resource->start_time->toW3cString()
				: null
			),
			'playback-video-url' => $resource->playbackVideoUrl,
		];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
	{
		return [
			'seminar-product-sponsors' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['seminar-product-sponsors']),
				self::DATA => function () use ($resource) {
					return $resource->seminarProductSponsors;
				},
			],
			'speaker' => [
				self::SHOW_SELF => true,
				self::SHOW_RELATED => true,
				self::SHOW_DATA => isset($includeRelationships['speaker']),
				self::DATA => function () use ($resource) {
					return $resource->speaker;
				},
			]
		];
	}
}
