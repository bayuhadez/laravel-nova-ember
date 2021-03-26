<?php

namespace App\JsonApi\Adapters;

use App\Models\ProductStock;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductStockAdapter extends BaseAdapter
{
    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    protected $dates = ['created-at', 'updated-at', 'datetime'];

    /**
     * @var array
     */
    protected $relationships = [
        'product',
        'rack',
        'stockDivision',
        'productStockMovements'
    ];

    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [
      'sortFifoByProduct',
      'sortFifoByProductOut',
    ];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new ProductStock(), $paging);
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return void
     */
    protected function filter($query, Collection $filters)
    {
        $this->filterWithScopes($query, $filters);
    }

    public function product()
    {
        return $this->belongsTo();
    }

    public function rack()
    {
        return $this->belongsTo();
    }

    public function stockDivision()
    {
        return $this->belongsTo();
    }

    public function productStockMovements()
    {
        return $this->hasMany();
    }

}
