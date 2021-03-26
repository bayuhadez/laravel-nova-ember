<?php

namespace App\JsonApi\Adapters;

use App\Models\Rack;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class RackAdapter extends BaseAdapter
{

    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $relationships = ['warehouse'];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new Rack(), $paging);
    }
    
    // Relationships [
    public function warehouse()
	{
		return $this->belongsTo();
    }
    
    public function productStocks()
    {
        return $this->hasMany();
    }
    // ]

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
