<?php

namespace App\JsonApi\Adapters;

use App\Models\SalesOrder;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SalesOrderAdapter extends BaseAdapter
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
        'customer',
        'company',
        'user',
        'productSalesOrders',
        'warehouseStaff',
        'sales',
        'deliveryRecipientCustomer',
        'deliveryAddress',
    ];

    protected $dates = ['created-at', 'updated-at', 'ordered-at'];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new SalesOrder(), $paging);
    }
    
    // Relationships [
    public function productSalesOrders()
    {
		return $this->hasMany();
    }

    public function createdBy()
    {
		return $this->belongsTo();
    }

    public function customer()
    {
        return $this->belongsTo();
    }

    public function company()
    {
        return $this->belongsTo();
    }

    public function warehouseStaff()
    {
        return $this->belongsTo();
    }

    public function sales()
    {
        return $this->belongsTo();
    }

    public function deliveryRecipientCustomer()
    {
        return $this->belongsTo();
    }

    public function deliveryAddress()
    {
        return $this->belongsTo();
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
