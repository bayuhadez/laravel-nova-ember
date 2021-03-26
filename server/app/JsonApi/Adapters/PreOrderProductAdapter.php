<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PreOrderProductAdapter extends BaseAdapter
{

    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new \App\Models\PreOrderProduct(), $paging);
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

    /**
     * Get the requestOrder that owns the requestOrder.
     */
    public function preOrder()
    {
        return $this->belongsTo();
    }

    /**
     * Get the product that owns the requestOrder.
     */
    public function product()
    {
        return $this->belongsTo();
    }

    /**
     * Get the product that owns the requestOrder.
     */
    public function productUnit()
    {
        return $this->belongsTo();
    }

    /**
     * Get the unit that owns the requestOrder.
     */
    public function unit()
    {
        return $this->belongsTo();
    }

}
