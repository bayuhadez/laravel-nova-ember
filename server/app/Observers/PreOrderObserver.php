<?php

namespace App\Observers;

use App\Lib\Functions;
use App\Models\PreOrder;
use Auth;
use Carbon\Carbon;

class PreOrderObserver
{
    /**
     * Handle the pre order "creating" event
     *
     * @param \App\Models\PreOrder
     * @return void
     */
    public function creating(PreOrder $preOrder)
    {
        if (empty($preOrder->number)) {
            $preOrder->number = Functions::generateCode(
                10,
                true,
                $preOrder->getTable(),
                'number'
            );
        }

        $preOrder->status = $preOrder->status ?? PreOrder::STATUS_NOT_DONE;
        $preOrder->total = $preOrder->total ?? 0;
        $preOrder->rounded_total = $preOrder->rounded_total ?? 0;
        $preOrder->created_by = $preOrder->created_by ?? Auth::user()->id;
        $preOrder->ordered_at = $preOrder->ordered_at ?? Carbon::today()->toDateString();
    }

    /**
     * Handle the pre order "created" event.
     *
     * @param  \App\Models\PreOrder  $preOrder
     * @return void
     */
    public function created(PreOrder $preOrder)
    {
        //
    }

    /**
     * Handle the pre order "updated" event.
     *
     * @param  \App\Models\PreOrder  $preOrder
     * @return void
     */
    public function updated(PreOrder $preOrder)
    {
        //
    }

    /**
     * Handle the pre order "deleted" event.
     *
     * @param  \App\Models\PreOrder  $preOrder
     * @return void
     */
    public function deleted(PreOrder $preOrder)
    {
        //
    }

    /**
     * Handle the pre order "restored" event.
     *
     * @param  \App\Models\PreOrder  $preOrder
     * @return void
     */
    public function restored(PreOrder $preOrder)
    {
        //
    }

    /**
     * Handle the pre order "force deleted" event.
     *
     * @param  \App\Models\PreOrder  $preOrder
     * @return void
     */
    public function forceDeleted(PreOrder $preOrder)
    {
        //
    }
}
