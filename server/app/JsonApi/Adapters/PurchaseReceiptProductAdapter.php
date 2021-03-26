<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BillItemAdapter extends BaseAdapter
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
        parent::__construct(new \App\Models\BillItem(), $paging);
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
     * Get the company that owns the billItem.
     */
    public function company()
    {
        return $this->belongsTo();
    }

    /**
     * Get the bill that owns the billItem.
     */
    public function bill()
    {
        return $this->belongsTo();
    }

    /**
     * Get the product that owns the billItem.
     */
    public function product()
    {
        return $this->belongsTo();
    }

    /**
     * Get the location that owns the billItem.
     */
    public function location()
    {
        return $this->belongsTo();
    }

    /**
     * Get the rack that owns the billItem.
     */
    public function rack()
    {
        return $this->belongsTo();
    }

}
