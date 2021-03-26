<?php

namespace App\JsonApi\Adapters;

use App\Models\TransactionReceipt;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TransactionReceiptAdapter extends BaseAdapter
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
        parent::__construct(new TransactionReceipt(), $paging);
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
     * @return BelongsTo Relation
     */
    protected function customer()
    {
        return $this->belongsTo();
    }

    /**
     * @return BelongsTo Relation
     */
    protected function supplier()
    {
        return $this->belongsTo();
    }

    /**
     * @return HasOne Relation
     */
    protected function purchaseReceipt()
    {
        return $this->hasOne();
    }

    /**
     * @return hasMany Relation
     */
    protected function productTransactionReceipts()
    {
        return $this->hasMany();
    }

    /**
     * @return hasMany Relation
     */
    protected function serviceTransactionReceipts()
    {
        return $this->hasMany();
    }
}
