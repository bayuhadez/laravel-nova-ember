<?php

namespace App\JsonApi\Validators;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class SalesReceiptValidator extends AbstractValidators
{

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'company',
        'created-by',
        'created-by.person',
        'transaction-receipt',
        'transaction-receipt.customer',
        'transaction-receipt.customer.person',
        'transaction-receipt.customer.sales-orders',
        'transaction-receipt.customer.sales-orders.sales',
        'transaction-receipt.customer.sales-orders.sales.person',
        'sales-receipt-services',
        'sales-receipt-services.sales-order-service',
        'product-sales-receipts',
        'product-sales-receipts.product-sales-order',
        'transaction-receipt.service-transaction-receipts',
        'transaction-receipt.service-transaction-receipts.sales-order-service',
        'transaction-receipt.product-transaction-receipts',
        'transaction-receipt.product-transaction-receipts.product-sales-order',
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
        'inCompanyAndDescendant'
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
            'company' => ['required'],
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
