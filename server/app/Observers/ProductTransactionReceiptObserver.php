<?php

namespace App\Observers;

use App\Models\PurchaseReceipt;
use App\Models\ProductTransactionReceipt;

class ProductTransactionReceiptObserver
{
    /**
     * Handle the product transaction receipt "created" event.
     *
     * @param  \App\Models\ProductTransactionReceipt  $productTransactionReceipt
     * @return void
     */
    public function created(ProductTransactionReceipt $productTransactionReceipt)
    {
        // update purchaseReceipt
        $purchaseReceipt = $productTransactionReceipt->purchaseReceipt;

        if (!empty($purchaseReceipt)) {
            // trigger observer to run updateSubTotalAndTotal()
            $purchaseReceipt->touch();
        }
    }

    /**
     * Handle the product transaction receipt "updated" event.
     *
     * @param  \App\Models\ProductTransactionReceipt  $productTransactionReceipt
     * @return void
     */
    public function updated(ProductTransactionReceipt $productTransactionReceipt)
    {
        // update purchaseReceipt
        $purchaseReceipt = $productTransactionReceipt->purchaseReceipt;

        if (!empty($purchaseReceipt)) {
            // trigger observer to run updateSubTotalAndTotal()
            $purchaseReceipt->touch();
        }
    }

    /**
     * Handle the product transaction receipt "deleted" event.
     *
     * @param  \App\Models\ProductTransactionReceipt  $productTransactionReceipt
     * @return void
     */
    public function deleted(ProductTransactionReceipt $productTransactionReceipt)
    {
        // update purchaseReceipt
        $purchaseReceipt = $productTransactionReceipt->purchaseReceipt;

        if (!empty($purchaseReceipt)) {
            // trigger observer to run updateSubTotalAndTotal()
            $purchaseReceipt->touch();
        }
    }

    /**
     * Handle the product transaction receipt "restored" event.
     *
     * @param  \App\Models\ProductTransactionReceipt  $productTransactionReceipt
     * @return void
     */
    public function restored(ProductTransactionReceipt $productTransactionReceipt)
    {
        //
    }

    /**
     * Handle the product transaction receipt "force deleted" event.
     *
     * @param  \App\Models\ProductTransactionReceipt  $productTransactionReceipt
     * @return void
     */
    public function forceDeleted(ProductTransactionReceipt $productTransactionReceipt)
    {
        //
    }
}
