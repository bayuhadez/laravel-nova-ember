<?php

namespace App\JsonApi\Adapters;

use App\Models\Company;
use App\Models\Product;
use App\JsonApi\Adapters\BaseAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductAdapter extends BaseAdapter
{

    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [
        'stockAtMinimumOrLess',
        'outOfStock',
        'inCompany',
        'codeLike',
        'nameLike',
        'skuLike',
        'basePriceFilter',
        'priceFilter',
        'stockFilter',
    ];

    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [
        'name',
        'description',
        'price',
        'stock',
        'minimum_stock'
    ];

    /*
     * @var array
     */
    protected $relationships = [
        'productCategories',
        'company',
        'user',
        'chatRooms',
        'productMetaValues',
        'unit',
        'units',
        'productUnits',
    ];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new Product(), $paging);
    }

    /*
     * @return BelongsTo Relation
     */
    protected function productCategories()
    {
        return $this->hasMany();
    }

    /*
     * @return BelongsTo Relation
     */
    protected function user()
    {
        return $this->belongsTo();
    }

    protected function seminarProductMeta()
    {
        return $this->hasOne();
    }

    /*
     * @return BelongsTo Relation
     */
    protected function productBanner()
    {
        return $this->hasOne();
    }

    /*
     * @return BelongsTo Relation
     */
    protected function creator()
    {
        return $this->belongsTo();
    }

    /*
     * @return hasMany Relation
     */
    protected function users()
    {
        return $this->hasMany();
    }

    /*
     * @return hasMany Relation
     */
    protected function chatRooms()
    {
        return $this->hasMany();
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
    protected function companyProducts()
    {
        return $this->hasMany();
    }
    /*
     * @return BelongsTo Relation
     */
    protected function expeditionProducts()
    {
        return $this->hasMany();
    }

    /*
     * @return Hasmany Relation
     */
    protected function productMetaValues()
    {
        return $this->hasMany();
    }

    /*
     * @return Hasmany Relation
     */
    protected function productUnits()
    {
        return $this->hasMany();
    }

    /*
     * @return Hasmany Relation
     */
    protected function units()
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
        $this->filterWithScopes($query, $filters);

        $user = auth('api')->user();

        if (!empty($user)) {
            $companyId = $user->getCurrentCompanyId();
        } else {
            $companyId = config('app.default_company_id');
        }

        // default status
        $statusIds = [
            Product::STATUS_APPROVED,
        ];

        if (false) {
            $query->where(function ($query) use ($statusIds, $user) {

                $query->whereIn('status', $statusIds);

                if (!empty($user)) {

                    if ($user->hasRole('mentor')) {

                        $query->orWhere(function ($query) use ($user) {
                            $query->whereIn(
                                'status',
                                [
                                    Product::STATUS_REJECTED,
                                    Product::STATUS_PROPOSED,
                                ]
                            );
                            $query->where('user_id', $user->id);
                        });

                    }

                }

            });

            // filter:
            if ($filters->has('name')) {
                $query->where('products.name', 'like', '%' . $filters->get('name') . '%');
            }

            if ($filters->has('ids')) {
                $productIds = explode(",", $filters->get('ids'));
                $query->whereIn('products.id', $productIds);
            }

            if ($filters->has('upcoming')) {
                $query->whereHas('seminarProductMeta', function ($q) {
                    $q->upcoming();
                });
            }

            if (!empty($user)) {

                if ($filters->has('purchased')) {
                    if ($filters->get('purchased') == 'true') {
                        $query->whereHas('users', function ($query) use ($user) {
                            $query->where('users.id', $user->id);
                        });
                    }
                }

                if ($filters->has('streamKey')) {
                    $query->whereHas('seminarProductMeta', function ($query) use ($filters) {
                        $query->where('seminar_product_metas.stream_key', $filters->get('streamKey'));
                    });
                }

                if ($user->hasRole('mentor')) {

                    $query->orWhere(function ($query) use ($user) {
                        $query->whereIn(
                            'status',
                            [
                                Product::STATUS_REJECTED,
                                Product::STATUS_PROPOSED,
                            ]
                        );
                        $query->where('user_id', $user->id);
                    });

                }
            }
        } 
    }

    protected function saved($product, $resource)
    {
        $base64File = $resource->get('image-file');

        if (!empty($base64File)) {
            $product->uploadImageFile($base64File, $resource->get('image-filename'));
        }
    }

    /**
     * @inheritDoc
     *
     * @return Product
     */
    /*
    protected function createRecord(ResourceObjectInterface $resource)
    {
        $product = new Product();
        return $product;
    }
     */

}
