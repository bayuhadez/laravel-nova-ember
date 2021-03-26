<?php

namespace App\JsonApi\Adapters;

use Carbon\Carbon;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PurchaseReceiptAdapter extends BaseAdapter
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

    protected $dates = [
        'created-at',
        'due-at',
        'receipted-at',
        'updated-at',
        'delivery-order-at',
        'receipt-letter-at',
    ];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new \App\Models\PurchaseReceipt(), $paging);
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
     * @param $value the value submitted by the client.
     * @param string $field the JSON API field name being deserialized.
     * @param Model $record the domain record being filled.
     * @return \DateTime|null
     */
    public function deserializeDate($value, $field, $record)
    {
        return (
            !is_null($value)
            ? (new Carbon($value))->setTimezone(new \DateTimeZone(config('app.timezone')))
            : null
        );
    }

    /**
     * Get the company that owns the purchaseReceipt.
     */
    public function company()
    {
        return $this->belongsTo();
    }

    /**
     * Get the createdBy that owns the purchaseReceipt.
     */
    public function createdBy()
    {
        return $this->belongsTo();
    }

    /**
     * Get the currency
     */
    public function currency()
    {
        return $this->belongsTo();
    }

    /**
     * Get the supplier that owns the transactionReceipt.
     */
    public function transactionReceipt()
    {
        return $this->belongsTo();
    }

    /**
     * Get the purchaseReceiptProducts for the purchaseReceipt.
     */
    public function purchaseReceiptProducts()
    {
        return $this->hasMany();
    }

    /**
     * Get the preOrders for the purchaseReceipt.
     */
    public function preOrders()
    {
        return $this->hasMany();
    }
}
