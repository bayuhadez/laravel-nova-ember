<?php

namespace App\JsonApi\Adapters;

use App\Models\Customer;
use App\Services\PhoneService;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CustomerAdapter extends BaseAdapter
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
    protected $relationships = [
        'company',
        'parentCustomer',
        'childrenCustomer',
        'person',
        'pic',
    ];

    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [
        'company_id',
        'inReadySO',
    ];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new Customer(), $paging);
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
    
    protected function companyCustomers()
    {
        return $this->hasMany();
    }

    /*
     * @return belongsTo Relation
     */
    protected function person()
    {
        return $this->belongsTo();
    }

    /*
     * @return belongsTo Relation
     */
    protected function pic()
    {
        return $this->belongsTo();
    }

    /**
     * @return belongsTo Relation
     */
    protected function parentCustomer()
    {
        return $this->belongsTo();
    }

    /**
     * @return hasMany Relation
     */
    public function childrenCustomer()
    {
        return $this->hasMany();
    }

    /**
     * @return hasMany Relation
     */
    public function salesOrders()
    {
        return $this->hasMany();
    }

}
