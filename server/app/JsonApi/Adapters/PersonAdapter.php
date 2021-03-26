<?php

namespace App\JsonApi\Adapters;

use App\Models\Person;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PersonAdapter extends BaseAdapter
{

    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new Person(), $paging);
    }

    public function user()
    {
        return $this->belongsTo();
    }

    public function staffs()
    {
        return $this->hasMany();
    }

    /*
     * @return BelongsTo Relation
     */
    public function regency()
    {
        return $this->belongsTo();
    }
    
    public function personAddresses()
    {
        return $this->hasMany();
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

}
