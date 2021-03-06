<?php

namespace App\JsonApi\Adapters;

use CloudCreativity\LaravelJsonApi\Adapter\AbstractResourceAdapter;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use Illuminate\Support\Collection;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;

class RegistrarAdapter extends AbstractResourceAdapter
{

	/**
	 * @inheritDoc
	 */
	protected function createRecord(ResourceObject $resource)
	{
		// TODO: Implement createRecord() method.
	}

	/**
	 * @inheritDoc
	 */
	protected function fillAttributes($record, Collection $attributes)
	{
		// TODO: Implement fillAttributes() method.
	}

	/**
	 * @inheritDoc
	 */
	protected function persist($record)
	{
		// TODO: Implement persist() method.
	}

	/**
	 * @inheritDoc
	 */
	protected function destroy($record)
	{
		// TODO: Implement destroy() method.
	}

	/**
	 * @inheritDoc
	 */
	public function query(EncodingParametersInterface $parameters)
	{
		// TODO: Implement query() method.
	}

	/**
	 * @inheritDoc
	 */
	public function exists($resourceId)
	{
		// TODO: Implement exists() method.
	}

	/**
	 * @inheritDoc
	 */
	public function find($resourceId)
	{
		// TODO: Implement find() method.
	}

	/**
	 * @inheritDoc
	 */
	public function findMany(array $resourceIds)
	{
		// TODO: Implement findMany() method.
	}

}
