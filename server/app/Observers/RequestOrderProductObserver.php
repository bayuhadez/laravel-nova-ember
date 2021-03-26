<?php

namespace App\Observers;

use App\Models\RequestOrderProduct;
use App\Models\ProductUnit;

class RequestOrderProductObserver
{
    public function creating(RequestOrderProduct $requestOrderProduct)
    {
        $productUnit = ProductUnit::where('product_id', $requestOrderProduct->product_id)
            ->where('unit_id', $requestOrderProduct->unit_id)
            ->first();

        if (!empty($productUnit)) {
            $requestOrderProduct->product_unit_id = $productUnit->id;
        }
    }

    /**
     * Handle the request order product "created" event.
     *
     * @param  \App\RequestOrderProduct  $requestOrderProduct
     * @return void
     */
    public function created(RequestOrderProduct $requestOrderProduct)
    {
        //
    }

    public function updating(RequestOrderProduct $requestOrderProduct)
    {
        $productUnit = ProductUnit::where('product_id', $requestOrderProduct->product_id)
            ->where('unit_id', $requestOrderProduct->unit_id)
            ->first();

        if (!empty($productUnit)) {
            $requestOrderProduct->product_unit_id = $productUnit->id;
        }
    }

    /**
     * Handle the request order product "updated" event.
     *
     * @param  \App\RequestOrderProduct  $requestOrderProduct
     * @return void
     */
    public function updated(RequestOrderProduct $requestOrderProduct)
    {
        //
    }

    /**
     * Handle the request order product "deleted" event.
     *
     * @param  \App\RequestOrderProduct  $requestOrderProduct
     * @return void
     */
    public function deleted(RequestOrderProduct $requestOrderProduct)
    {
        //
    }

    /**
     * Handle the request order product "restored" event.
     *
     * @param  \App\RequestOrderProduct  $requestOrderProduct
     * @return void
     */
    public function restored(RequestOrderProduct $requestOrderProduct)
    {
        //
    }

    /**
     * Handle the request order product "force deleted" event.
     *
     * @param  \App\RequestOrderProduct  $requestOrderProduct
     * @return void
     */
    public function forceDeleted(RequestOrderProduct $requestOrderProduct)
    {
        //
    }
}
