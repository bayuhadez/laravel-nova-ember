<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class RequestOrderAdapter extends BaseAdapter
{

    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Resource relationship fields that can be filled.
     *
     * @var array
     */
    protected $relationships = [
        'createdBy',
        'requestOrderProducts',
    ];

    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [
        'companyNameLike',
        'numberLike',
        'createdAtLike',
        'companyDivisionFilter',
        'statusFilter',
    ];

    protected $dates = ['created-at', 'updated-at'];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new \App\Models\RequestOrder(), $paging);
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
     * Get the company that owns the requestOrder.
     */
    public function company()
    {
        return $this->belongsTo();
    }

    /**
     * Get the user that is created the requestOrder.
     */
    public function createdBy()
    {
        return $this->belongsTo();
    }

    /**
     * Get the staff that owns the requestOrder.
     */
    public function staff()
    {
        return $this->belongsTo();
    }

    /**
     * Get the staffPosition that owns the requestOrder.
     */
    public function staffPosition()
    {
        return $this->belongsTo();
    }

    /**
     * Get the preOder that owns the requestOrder.
     */
    public function preOrder()
    {
        return $this->belongsTo();
    }

    /**
     * Get the requestOrderProducts for the requestOrder
     */
    public function requestOrderProducts()
    {
        return $this->hasMany('requestOrderProducts');
    }
}
