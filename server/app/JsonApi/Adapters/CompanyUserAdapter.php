<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CompanyUserAdapter extends BaseAdapter
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
        parent::__construct(new \App\Models\CompanyUser(), $paging);
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

    /*
     * @return hasMany Relation
     */
    protected function roles()
    {
        return $this->hasMany();
    }

    /*
     * @return hasMany Relation
     */
    protected function stockDivisions()
    {
        return $this->hasMany();
    }

    /*
     * @return belongsTo Relation
     */
    protected function company()
    {
        return $this->belongsTo();
    }

    /*
     * @return belongsTo Relation
     */
    protected function user()
    {
        return $this->belongsTo();
    }

}
