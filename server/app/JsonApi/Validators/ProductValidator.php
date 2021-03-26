<?php

namespace App\JsonApi\Validators;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class ProductValidator extends AbstractValidators
{

    protected $allowedFilteringParameters = [
        'ids',
        'name',
        'purchased',
        'upcoming',
        'streamKey',
        'stockAtMinimumOrLess',
        'outOfStock',
        'inCompany',
        'inCompanies',
        'codeLike',
        'nameLike',
        'skuLike',
        'basePriceFilter',
        'priceFilter',
        'stockFilter',
    ];

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'expedition-products',
        'product-meta-values',
        'units',
        'companies',
        'company-products',
        'company-products.company',
        'product-units',
        'product-units.unit',
    ];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [
        'created-at',
        'code',
        'name',
    ];

    /**
     * Get resource validation rules.
     *
     * @param mixed|null $record
     *      the record being updated, or null if creating a resource.
     * @return mixed
     */
    protected function rules($record = null): array
    {
        return [
            'name' => 'required',
        ];
    }

    /**
     * Get query parameter validation rules.
     *
     * @return array
     */
    protected function queryRules(): array
    {
        return [
            'filter.name' => 'filled|string',
            'filter.ids' => 'filled|min:1',
        ];
    }

}
