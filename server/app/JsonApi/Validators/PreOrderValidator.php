<?php

namespace App\JsonApi\Validators;

use CloudCreativity\LaravelJsonApi\Rules\HasOne;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class PreOrderValidator extends AbstractValidators
{

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'pre-order-products',
        'pre-order-products.product',
        'request-orders',
        'company',
        'supplier',
        'supplier.company',
        'created-by',
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
    protected $allowedFilteringParameters = [
        'inPurchaseReceipts',
        'doesntHavePurchaseReceipts',
        'companyNameLike',
        'numberLike',
        'createdByNameLike',
        'supplierCompanyNameLike',
        'totalLike',
        'statusFilter',
        'companyDivisionFilter',
        'notIn',
        'supplier_id',
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
            'company' => [
                'required',
                new HasOne()
            ],
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
