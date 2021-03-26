<?php

namespace App\JsonApi\Adapters;

use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class PreOrderAdapter extends BaseAdapter
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
        'companyNameLike',
        'numberLike',
        'createdByNameLike',
        'supplierCompanyNameLike',
        'totalLike',
        'statusFilter',
        'companyDivisionFilter',
    ];

    protected $dates = ['created-at', 'updated-at', 'ordered-at', 'due-at'];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new \App\Models\PreOrder(), $paging);
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
     * Get the company that owns the pre order.
     */
    public function company()
    {
        return $this->belongsTo();
    }

    /**
     * Get the user creator that owns the pre order.
     */
    public function createdBy()
    {
        return $this->belongsTo();
    }

    /**
     * Get the currency that for pre order.
     */
    public function currency()
    {
        return $this->belongsTo();
    }

    /**
     * Get the supplier that owns the pre order.
     */
    public function supplier()
    {
        return $this->belongsTo();
    }

    /**
     * The users that belong to the role.
     */
    public function requestOrders()
    {
        return $this->hasMany();
    }

    /**
     * Get the preOrderProducts for the pre order
     */
    public function preOrderProducts()
    {
        return $this->hasMany();
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

}
