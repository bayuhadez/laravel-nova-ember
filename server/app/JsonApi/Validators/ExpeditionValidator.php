<?php

namespace App\JsonApi\Validators;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class ExpeditionValidator extends AbstractValidators
{

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'expedition-products',
        'service-category',
        'regency',
        'currency',
        'country',
        'province',
    ];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [];

    /**
     * The filters a client is allowed send.
     *
     * @var string[]|null
     *      the allowed filters, an empty array for none allowed, or null to allow all.
     */
    protected $allowedFilteringParameters = [];

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
            'code' => 'nullable|max:16',
            'name' => 'required|max:30',
            'address' => 'nullable|max:128',
            'regency-id' => 'nullable|integer',
            'regency-id' => 'nullable|integer',
            'expedition-category-id' => 'nullable|integer',
            'fax-number' => 'nullable|digits_between:1,20',
            'mobile-number' => 'nullable|digits_between:1,20',
            'telephone-number' => 'nullable|digits_between:1,20',
            'pic' => 'nullable|max:30',
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
            //
        ];
    }

}
