<?php

namespace App\JsonApi\Adapters;

use App\Models\ProductCategory;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductCategoryAdapter extends BaseAdapter
{

    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new ProductCategory, $paging);
    }

    /*
     * @return BelongsTo Relation
     */
    protected function company()
    {
        return $this->belongsTo();
    }

    /*
     * @return BelongsTo Relation
     */
    protected function parent()
    {
        return $this->belongsTo();
    }

    /*
     * @return HasMany Relation
     */
    protected function children()
    {
        return $this->hasMany();
    }

    /*
     * @return HasMany Relation
     */
    protected function products()
    {
        return $this->hasMany();
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return void
     */
    protected function filter($query, Collection $filters)
    {
        if ($filters->has('name')) {
            $query->where(
                'product_categories.name',
                'like',
                '%' . $filters->get('name') . '%'
            );
        }

        if ($filters->has('company_id')) {
            $query->where(
                'product_categories.company_id',
                '=',
                $filters->get('company_id')
            );
        }
    }

    protected function creating(ProductCategory $productCategory, $resource)
    {
        $company = auth('api')->user()->currentOrFirstCompany;
        $productCategory->company_id = $company->id;
    }

}
