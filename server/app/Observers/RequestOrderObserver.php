<?php

namespace App\Observers;

use App\Lib\Functions;
use App\Models\Company;
use App\Models\RequestOrder;
use Auth;

class RequestOrderObserver
{
    public function creating(RequestOrder $requestOrder)
    {
        $requestOrder->created_by = Auth::user()->id;
        $requestOrder->division = Company::find($requestOrder->company_id)->division;
        if (is_null($requestOrder->number)) {
            $requestOrder->number = Functions::generateCode();
        }
    }

    /**
     * Handle the request order "created" event.
     *
     * @param  \App\Models\RequestOrder  $requestOrder
     * @return void
     */
    public function created(RequestOrder $requestOrder)
    {
        //
    }

    public function updating(RequestOrder $requestOrder)
    {
        //
    }

    /**
     * Handle the request order "updated" event.
     *
     * @param  \App\Models\RequestOrder  $requestOrder
     * @return void
     */
    public function updated(RequestOrder $requestOrder)
    {
        //
    }

    /**
     * Handle the request order "deleted" event.
     *
     * @param  \App\Models\RequestOrder  $requestOrder
     * @return void
     */
    public function deleted(RequestOrder $requestOrder)
    {
        //
    }

    /**
     * Handle the request order "restored" event.
     *
     * @param  \App\Models\RequestOrder  $requestOrder
     * @return void
     */
    public function restored(RequestOrder $requestOrder)
    {
        //
    }

    /**
     * Handle the request order "force deleted" event.
     *
     * @param  \App\Models\RequestOrder  $requestOrder
     * @return void
     */
    public function forceDeleted(RequestOrder $requestOrder)
    {
        //
    }
}
