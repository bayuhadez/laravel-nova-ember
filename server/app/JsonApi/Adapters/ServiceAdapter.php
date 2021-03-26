<?php

namespace App\JsonApi\Adapters;

use App\Models\Service;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ServiceAdapter extends BaseAdapter
{

    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    /*
     * @var array
     */
    protected $relationships = [
        'service-category',
    ];

    protected $filterScopes = [
        'codeLike',
        'nameLike',
        'decriptionLike',
        'serviceCategoryNameLike',
    ];

    /*
     * @return HasMany Relation
     */
    protected function companyServices()
    {
        return $this->hasMany();
    }

    /*
     * @return BelongsTo Relation
     */
    protected function serviceCategory()
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
        parent::__construct(new Service(), $paging);
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
            $query->where('services.name', 'like', '%' . $filters->get('name') . '%');
        }
    }

}
