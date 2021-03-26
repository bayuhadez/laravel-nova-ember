<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Models\Staff;

class StaffAdapter extends BaseAdapter
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
        'codeLike',
        'personFirstNameLike',
        'personLastNameLike',
        'personAddressLike',
        'personPhoneLike',
        'personRegencyNameLike',
        'companyNameLike',
        'staffCategoryNameLike',
        'staffPositionNameLike',
        'inCompany',
    ];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new Staff(), $paging);
    }

    public function company()
    {
        return $this->belongsTo();
    }
    
    public function person()
    {
        return $this->belongsTo();
    }

    public function staffCategories()
    {
        return $this->hasMany();
    }
    
    public function staffPositions()
    {
        return $this->hasMany();
    }

    public function companyCustomers()
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
