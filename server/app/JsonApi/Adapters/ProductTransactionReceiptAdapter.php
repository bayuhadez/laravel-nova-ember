<?php

namespace App\JsonApi\Adapters;

use App\Models\ProductTransactionReceipt;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductTransactionReceiptAdapter extends BaseAdapter
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
    protected $filterScopes = [];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new ProductTransactionReceipt(), $paging);
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
    public function preOrderProduct()
    {
        return $this->belongsTo();
    }

    /**
     * @return belongsTo Relation
     */
    public function productSalesOrder()
    {
        return $this->belongsTo();
    }

    /**
     * @return belongsTo Relation
     */
    protected function product()
    {
        return $this->belongsTo();
    }

    /**
     * @return belongsTo Relation
     */
    protected function productUnit()
    {
        return $this->belongsTo();
    }

    /**
     * @return belongsTo Relation
     */
    protected function transactionReceipt()
    {
        return $this->belongsTo();
    }

    public function productStockMovements()
    {
        return $this->hasMany();
    }

}
