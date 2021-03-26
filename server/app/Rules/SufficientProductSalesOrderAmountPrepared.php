<?php

namespace App\Rules;

use App\Models\ProductSalesOrder as ProductSalesOrder;
use App\Models\ProductStockMovement as ProductStockMovement;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

/**
     * Validates whether the amount prepared of a PSO
     * is lesser than amount approved and order quantity
     *
     * @param int $productSalesOrderId
     * @param int $amountApproved
     * @param int $orderQuantity
     * 
     * @return bool
     */
class SufficientProductSalesOrderAmountPrepared implements Rule
{
    public $productSalesOrderId;
    public $amountApproved;
    public $orderQuantity;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        $productSalesOrderId,
        $amountApproved,
        $orderQuantity)
    {
        $this->productSalesOrderId = $productSalesOrderId;
        $this->amountApproved = $amountApproved;
        $this->orderQuantity = $orderQuantity;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $productSalesOrder = ProductSalesOrder::find($productSalesOrderId);

        $outProductStockMovements = $productSalesOrder
            ->productStockMovements
            ->where('in_or_out', ProductStockMovement::OUT)
            ->collect();

        /**
         * Loop through all related prepared stocks to calculate
         * the actual amount prepared
         */
        $totalOut = 0;
        foreach ($outProductStockMovements as $psm) {
            $totalOut += $psm['quantity'];
        }

        $finalAmountPrepared = $value + $totalOut
        return (
            ($finalAmountPrepared <= $amountApproved)
            && ($finalAmountPrepared <= $orderQuantity)
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Jumlah barang diambil lebih banyak daripada jumlah diakui';
    }
}
