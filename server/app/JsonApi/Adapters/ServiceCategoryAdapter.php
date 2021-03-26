<?php

namespace App\JsonApi\Adapters;

use App\Models\ServiceCategory;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ServiceCategoryAdapter extends BaseAdapter
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
    protected $filterScopes = [
        'nameLike',
    ];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new ServiceCategory(), $paging);
    }

    /*
     * @return HasMany Relation
     */
    protected function service()
    {
        return $this->hasOne();
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return void
     */
    protected function filter($query, Collection $filters)
    {
        $this->filterWithScopes($query, $filters);
        if ($filters->has('name')) {
            $query->where('service_categories.name', 'like', '%' . $filters->get('name') . '%');
        }
    }

}
