<?php

namespace App\Services;

use App\Models\ProductStock;
use App\Models\ProductStockMovement;

class ProductStockService
{
    /**
     * Return latest productStock in same rack, same stock division
     *
     * @param integer $productId
     * @param integer $rackId
     * @param integer $stockDivisionId
     *
     * @return ProductStock|null
     */
    public static function getLatestInSameRackAndStockDivision(
        $productId,
        $rackId,
        $stockDivisionId
    ) {
        return ProductStock::latest('datetime')
            ->where('product_id', $productId)
            ->where('rack_id', $rackId)
            ->where('stock_division_id', $stockDivisionId)
            ->first();
    }

    /**
     * Return ProductStock that is related with ProductStockMovement
     * since the productStock doesnt have direct relationship with productStockMovement
     * the similarity based on :
     * - productStockMovement.transactionReceipt.created_at
     * - productStockMovement.product_id
     * - productStockMovement.rack_id
     * - productStockMovement.stock_division_id
     *
     * @param ProductStockMovement $productStockMovement
     * @return mixed (ProductStock|null)
     */
    public static function getProductStockRelatedWithProductStockMovement(
        ProductStockMovement $productStockMovement
    ) {
        $transactionReceipt = $productStockMovement->transactionReceipt;

        if (!empty($transactionReceipt)) {
            $datetime = $transactionReceipt->created_at;

            return ProductStock::where('datetime', $datetime->toDateTimeString())
                ->where('product_id', $productStockMovement->product_id)
                ->where('rack_id', $productStockMovement->rack_id)
                ->where('stock_division_id', $productStockMovement->stock_division_id)
                ->first();
        }

        return null;
    }
}
