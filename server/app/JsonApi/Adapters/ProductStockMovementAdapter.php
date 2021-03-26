<?php

namespace App\JsonApi\Adapters;

use App\Models\ProductStockMovement;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductStockMovementAdapter extends BaseAdapter
{
    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    protected $dates = ['created-at', 'updated-at', 'datetime'];

    /**
     * @var array
     */
    protected $relationships = [
        'product',
        'rack',
        'stockDivision',
        'purchaseRecipt',
    ];

    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [
        'SortFifoByProduct',
        'forProductStock',
    ];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new ProductStockMovement(), $paging);
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

    /** relationships **/
    public function customer()
    {
        return $this->belongsTo();
    }

    public function product()
    {
        return $this->belongsTo();
    }

    public function rack()
    {
        return $this->belongsTo();
    }

    public function stockDivision()
    {
        return $this->belongsTo();
    }

    public function productSalesOrder()
    {
        return $this->belongsTo();
    }

    public function productStock()
    {
        return $this->belongsTo();
    }

    public function user()
    {
        return $this->belongsTo();
    }

    protected function purchaseReceipt()
    {
        return $this->hasOne();
    }

    protected function salesReceipt()
    {
        return $this->hasOne();
    }

    protected function productTransactionReceipt()
    {
        return $this->belongsTo();
    }
}
