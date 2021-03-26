<?php

namespace App\Observers;

use App\Models\ProductStock;
use App\Models\ProductStockMovement;
use App\Services\ProductStockService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductStockMovementObserver
{
    /**
     * Handle the product 'creating' event
     *
     * @param \App\Models\Product
     * @return void
     */
    public function creating(ProductStockMovement $productStockMovement)
    {
        $user = auth()->user();

        if (!empty($user)) {
            // set user_id as author
            if (empty($productStockMovement->user_id)) {
                $productStockMovement->user_id = $user->id;
            }
        }

        if (empty($productStockMovement->datetime)) {
            $productStockMovement->datetime = Carbon::now()->toDateTimeString();
        }
    }

    /**
     * Handle the ProductStockMovement "created" event.
     *
     * @param  \App\Models\ProductStockMovement  $productStockMovement
     * @return void
     */
    public function created(ProductStockMovement $productStockMovement)
    {
        if (empty($productStockMovement->productStock)) {
            $productStock = ProductStockService::getProductStockRelatedWithProductStockMovement(
                $productStockMovement
            );

            if (!empty($productStockMovement->transactionReceipt)) {
                $transactionReceipt = $productStockMovement->transactionReceipt;
                $datetime = $transactionReceipt->created_at;
            }
    
            if (empty($productStock)) {
                $productStock = new ProductStock();
                $productStock->datetime = (
                    isset($datetime)
                    ? $datetime->toDateTimeString() 
                    : $productStockMovement->datetime
                );
                $productStock->product_id = $productStockMovement->product_id;
                $productStock->rack_id = $productStockMovement->rack_id;
                $productStock->stock_division_id = $productStockMovement->stock_division_id;
                $productStock->quantity = $productStockMovement->quantity;
            } else {
                $productStock->quantity += $productStockMovement->quantity;
            }
            $productStock->save();
    
            $productStockMovement->product_stock_id = $productStock->id;
            $productStockMovement->saveQuietly();
        } else {
            $productStock = $productStockMovement->productStock;
            if ($productStockMovement->in_or_out) {
                $productStock->quantity += $productStockMovement->quantity;
            } else {
                $productStock->quantity -= $productStockMovement->quantity;
            }
            $productStock->save();
        }
    }

    public function updated(ProductStockMovement $productStockMovement)
    {
        $productStock = ProductStock::find($productStockMovement->product_stock_id);

        $quantityDiff = $productStockMovement->quantity - $productStockMovement->getOriginal('quantity');

        $productStock->quantity = $productStock->quantity + $quantityDiff;

        $productStock->save();
    }

    /**
     * Handle the ProductStockMovement "deleted" event.
     *
     * @param  \App\Models\ProductStockMovement  $productStockMovement
     * @return void
     */
    public function deleted(ProductStockMovement $productStockMovement)
    {
        $productStock = ProductStock::find($productStockMovement->product_stock_id);

        if (!empty($productStock)) {
            $productStock->quantity -= $productStockMovement->quantity;
            $productStock->save();
        }
    }

}
