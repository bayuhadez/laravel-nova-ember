<?php

namespace App\JsonApi\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class StockDivisionByCompanyDivisionSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'stock-division-by-company-divisions';

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return $resource->id;
    }

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'company-name' => $resource->companyName,
            'division' => $resource->division,
            'stock-division-count' => $resource->stockDivisionCount,
        ];
    }
}
