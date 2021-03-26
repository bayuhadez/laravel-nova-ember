<?php

namespace App\JsonApi\Adapters;

use App\Models\CompanyCustomer;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CompanyCustomerAdapter extends BaseAdapter
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
     * @var array
     */
    protected $relationships = [
        'company',
        'customer',
        'staffs',
    ];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new CompanyCustomer(), $paging);
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
     * @return belongsTo Relation
     */
    protected function company()
    {
        return $this->belongsTo();
    }

    /**
     * @return belongsTo Relation
     */
    protected function customer()
    {
        return $this->belongsTo();
    }

    /**
     * @return belongsTo Relation
     */
    protected function staffs()
    {
        return $this->hasMany();
    }

}
