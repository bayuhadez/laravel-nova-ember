<?php

namespace App\JsonApi\Validators;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;
use App\Rules\SufficientProductSalesOrderAmountPrepared;

class ProductSalesOrderValidator extends AbstractValidators
{

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'sales-order',
        'stock-division',
        'product',
        'product-transaction-receipt',
        'product-stock-movements',
        'product-stock-movements.rack',
        'product-stock-movements.stock-division',
        'product-stock-movements.product-stock',
        'sales-order.company',
        'sales-order.customer.company',
        'sales-order.delivery-recipient-customer',
        'sales-order.delivery-recipient-customer.company',
        'sales-order.delivery-address',
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
        'inSalesOrders',
        'inSalesOrderWithStatus',
        'notInOtherTransactionReceipt',
        'salesOrderCompanyNameLike',
        'salesOrderNumberLike',
        'salesOrderCustomerPersonNameLike',
        'salesOrderDeliveryRecipientNameLike',
        'salesOrderDeliveryAddressLike',
        'salesOrderDeliveryAddressRegencyLike',
        'productNameLike',
        'stockDivisionNameLike',
        'statusTextLike',
        'salesOrderCompanyDivisionFilter',
        'salesOrderRequiresDeliveryFilter',
        'statusFilter',
        'status',
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
        $rules = [];

        if (!empty($record)) {
            // edit record
            $rules['amount_prepared'] = 
                new SufficientProductSalesOrderAmountPrepared(
                    $record->id,
                    $record->amount_approved,
                    $record->order_quantity,
                );
        }

        return $rules;
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
