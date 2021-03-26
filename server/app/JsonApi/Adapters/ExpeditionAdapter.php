<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Models\Expedition;

class ExpeditionAdapter extends BaseAdapter
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
    /*
    * @var array
    */
    protected $relationships = [
        'expedition-category',
        'expedition-products',
        'fax',
        'mobilephone',
        'telephone',
        'regency',
        'province',
    ];

    protected $filterScopes = [];

    public function expeditionCategory()
    {
        return $this->belongsTo();
    }

    public function expeditionProducts()
    {
        return $this->hasMany();
    }

    public function mobilephone()
    {
        return $this->belongsTo();
    }
    
    public function fax()
    {
        return $this->belongsTo();
    }

    public function telephone()
    {
        return $this->belongsTo();
    }

    /**
     * @return BelongsTo Relation
     */
    public function province()
    {
        return $this->belongsTo();
    }

    public function regency()
    {
        return $this->belongsTo();
    }

    public function currency()
    {
        return $this->belongsTo();
    }

    public function country()
    {
        return $this->belongsTo();
    }

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new Expedition(), $paging);
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
