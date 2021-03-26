<?php

namespace App\JsonApi\Adapters;

use Carbon\Carbon;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SalesReceiptAdapter extends BaseAdapter
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
        'updated-at',
        'due-at',
        'receipted-at',
    ];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new \App\Models\SalesReceipt(), $paging);
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
     * Get the company that owns the salesReceipt.
     */
    public function company()
    {
        return $this->belongsTo();
    }

    /**
     * Get the user creator that owns the sales.
     */
    public function createdBy()
    {
        return $this->belongsTo();
    }

    /**
     * Get the address of customer for the salesReceipt.
     */
    public function address()
    {
        return $this->belongsTo();
    }

    /**
     * Get the transaction receipt for the salesReceipt.
     */
    public function transactionReceipt()
    {
        return $this->belongsTo();
    }
}
