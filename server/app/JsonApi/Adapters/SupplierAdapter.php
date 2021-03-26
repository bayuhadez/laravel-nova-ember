<?php

namespace App\JsonApi\Adapters;

use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use App\Models\Supplier;

class SupplierAdapter extends BaseAdapter
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
        'currencyCode',
    ];

    /**
     *
     * @var array
     */
    protected $relationships = [
        'currency',
        'supplier-category',
        'company',
    ];

    /*
     * @return BelongsTo Relation
     */
    public function supplierCategory()
    {
        return $this->belongsTo();
    }

    /*
     * @return BelongsTo Relation
     */
    public function currency()
    {
        return $this->belongsTo();
    }

    /*
     * @return BelongsTo Relation
     */
    public function user()
    {
        return $this->belongsTo();
    }

    /**
     * @return belongsTo Relation
     */
    public function company()
    {
        return $this->belongsTo();
    }

    /**
     * @return belongsTo Relation
     */
    public function pic()
    {
        return $this->belongsTo();
    }

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new Supplier(), $paging);
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
}
