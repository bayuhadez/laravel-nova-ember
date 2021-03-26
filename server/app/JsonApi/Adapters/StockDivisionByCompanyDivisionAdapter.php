<?php

namespace App\JsonApi\Adapters;

use CloudCreativity\LaravelJsonApi\Adapter\AbstractResourceAdapter;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use Illuminate\Support\Collection;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use App\JsonApi\Pagers\CollectionStrategy;
use App\Models\Company;
use App\JsonApi\Records\StockDivisionByCompanyDivision;

class StockDivisionByCompanyDivisionAdapter extends AbstractResourceAdapter
{
    public function __construct(CollectionStrategy $paging)
    {
        $this->paging = $paging;
    }

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
        $records = StockDivisionByCompanyDivision::get($parameters->toArray());
        $pagination = collect($parameters->getPaginationParameters());

        return $pagination->isEmpty()
            ? $records
            : $this->paginate($records, $parameters);

        return $records;
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

    /**
     * Return the result for a paginated query.
     *
     * @param Builder $query
     * @param EncodingParametersInterface $parameters
     * @return PageInterface
     */
    protected function paginate($collection, EncodingParametersInterface $parameters)
    {
        if (!$this->paging) {
            throw new RuntimeException('Paging is not supported on adapter: ' . get_class($this));
        }

        return $this->paging->paginate($collection, $parameters);
    }

}
