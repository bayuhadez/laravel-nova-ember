<?php

namespace App\JsonApi\Adapters;

use App\Models\ProductSalesReceipt;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductSalesReceiptAdapter extends BaseAdapter
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
    protected $relationships = [];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new ProductSalesReceipt(), $paging);
    }
    
    // Relationships [
    public function product()
    {
		  return $this->belongsTo();
    }
    
    public function salesReceipt()
    {
		  return $this->belongsTo();
    }

    public function productSalesOrder()
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
