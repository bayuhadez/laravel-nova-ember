<?php

namespace App\Observers;

use App\Lib\Functions;
use App\Models\SalesReceipt;
use App\Models\TransactionReceipt;
use Auth;
use Carbon\Carbon;

class SalesReceiptObserver
{
    /**
     * Handle the purchase receipt "creating" event.
     *
     * @param  \App\Models\SaleReceipt  $salesReceipt
     * @return void
     */
    public function creating(SalesReceipt $salesReceipt)
    {
        if (empty($salesReceipt->number)) {
            $salesReceipt->number = Functions::generateCode(
                10,
                true,
                $salesReceipt->getTable(),
                'number'
            );
        }

        $salesReceipt->total = $salesReceipt->total ?? 0;
        $salesReceipt->created_by = $salesReceipt->created_by ?? Auth::user()->id;
        $salesReceipt->receipted_at = $salesReceipt->receipted_at ?? Carbon::today()->toDateString();
    }

    /**
     * Handle the sales receipt "created" event.
     *
     * @param  \App\Models\SalesReceipt  $salesReceipt
     * @return void
     */
    public function created(SalesReceipt $salesReceipt)
    {
        //
    }

    /**
     * Handle the sales receipt "updated" event.
     *
     * @param  \App\Models\SalesReceipt  $salesReceipt
     * @return void
     */
    public function updated(SalesReceipt $salesReceipt)
    {
        //
    }

    /**
     * Handle the sales receipt "deleted" event.
     *
     * @param  \App\Models\SalesReceipt  $salesReceipt
     * @return void
     */
    public function deleted(SalesReceipt $salesReceipt)
    {
        //
    }

    /**
     * Handle the sales receipt "restored" event.
     *
     * @param  \App\Models\SalesReceipt  $salesReceipt
     * @return void
     */
    public function restored(SalesReceipt $salesReceipt)
    {
        //
    }

    /**
     * Handle the sales receipt "force deleted" event.
     *
     * @param  \App\Models\SalesReceipt  $salesReceipt
     * @return void
     */
    public function forceDeleted(SalesReceipt $salesReceipt)
    {
        //
    }
}
