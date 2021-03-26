<?php

namespace App\Observers;

use App\Lib\Functions;
use App\Models\Customer;
use App\Models\PurchaseReceipt;
use App\Models\TransactionReceipt;
use Auth;
use Carbon\Carbon;

class PurchaseReceiptObserver
{
    /**
     * Handle the purchase receipt "creating" event.
     *
     * @param  \App\Models\PurchaseReceipt  $purchaseReceipt
     * @return void
     */
    public function creating(PurchaseReceipt $purchaseReceipt)
    {
        if (empty($purchaseReceipt->number)) {
            $purchaseReceipt->number = Functions::generateCode(
                10,
                true,
                $purchaseReceipt->getTable(),
                'number'
            );
        }

        $purchaseReceipt->sub_total = $purchaseReceipt->sub_total ?? 0;
        $purchaseReceipt->total = $purchaseReceipt->total ?? 0;
        $purchaseReceipt->rounded_total = $purchaseReceipt->rounded_total ?? 0;
        $purchaseReceipt->created_by = $purchaseReceipt->created_by ?? Auth::user()->id;
        $purchaseReceipt->receipted_at = $purchaseReceipt->receipted_at ?? Carbon::today()->toDateString();
    }

    /**
     * Handle the purchase receipt "created" event.
     *
     * @param  \App\Models\PurchaseReceipt  $purchaseReceipt
     * @return void
     */
    public function created(PurchaseReceipt $purchaseReceipt)
    {
        // update total and subTotal
        $purchaseReceiptObj = PurchaseReceipt::withoutEvents(function () use ($purchaseReceipt) {
            $purchaseReceiptWE = PurchaseReceipt::find($purchaseReceipt->id);
            $purchaseReceiptWE->updateSubTotalAndTotal();
            return $purchaseReceiptWE;
        });
    }

    /**
     * Handle the purchase receipt "updated" event.
     *
     * @param  \App\Models\PurchaseReceipt  $purchaseReceipt
     * @return void
     */
    public function updated(PurchaseReceipt $purchaseReceipt)
    {
        // update total and subTotal
        $purchaseReceiptObj = PurchaseReceipt::withoutEvents(function () use ($purchaseReceipt) {
            $purchaseReceiptWE = PurchaseReceipt::find($purchaseReceipt->id);
            $purchaseReceiptWE->updateSubTotalAndTotal();
            return $purchaseReceiptWE;
        });
    }

    /**
     * Handle the purchase receipt "deleted" event.
     *
     * @param  \App\Models\PurchaseReceipt  $purchaseReceipt
     * @return void
     */
    public function deleted(PurchaseReceipt $purchaseReceipt)
    {
        //
    }

    /**
     * Handle the purchase receipt "restored" event.
     *
     * @param  \App\Models\PurchaseReceipt  $purchaseReceipt
     * @return void
     */
    public function restored(PurchaseReceipt $purchaseReceipt)
    {
        //
    }

    /**
     * Handle the purchase receipt "force deleted" event.
     *
     * @param  \App\Models\PurchaseReceipt  $purchaseReceipt
     * @return void
     */
    public function forceDeleted(PurchaseReceipt $purchaseReceipt)
    {
        //
    }
}
