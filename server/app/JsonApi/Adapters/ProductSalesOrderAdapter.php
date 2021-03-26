<?php

namespace App\JsonApi\Adapters;

use App\Models\ProductSalesOrder;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductSalesOrderAdapter extends BaseAdapter
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
        'product',
        'salesOrder',
        'stockDivision',
    ];

    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [
      'salesOrderCompanyNameLike',
      'salesOrderNumberLike',
      'salesOrderCustomerPersonNameLike',
      'salesOrderDeliveryRecipientNameLike',
      'salesOrderDeliveryAddressLike',
      'salesOrderDeliveryAddressRegencyLike',
      'productNameLike',
      'stockDivisionNameLike',
      'salesOrderCompanyDivisionFilter',
      'salesOrderRequiresDeliveryFilter',
      'statusFilter',
    ];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new ProductSalesOrder(), $paging);
    }
    
    // Relationships [
    public function product()
    {
		  return $this->belongsTo();
    }
    
    public function salesOrder()
    {
		  return $this->belongsTo();
    }

    public function stockDivision()
    {
		  return $this->belongsTo();
    }

    public function productTransactionReceipt()
    {
		  return $this->hasOne();
    }

    public function productStockMovements()
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
