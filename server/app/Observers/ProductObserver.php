<?php

namespace App\Observers;

use App\Models\Product;
use App\Lib\Functions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductObserver
{
    /**
     * Handle the product 'creating' event
     *
     * @param \App\Models\Product
     * @return void
     */
    public function creating(Product $product)
    {
        $user = auth()->user();

        if (!empty($user)) {

            // set user_id as author
            if (empty($product->user_id)) {
                $product->user_id = $user->id;
            }

            // set company_id
            if (
                empty($product->company_id) &&
                !is_null($user->currentOrFirstCompany->id)
            ) {
                $product->company_id = $user->currentOrFirstCompany->id;
            }
        }

        if (empty($product->status)) {
            $product->status = 1;
        }

        if (empty($product->price)) {
            $product->price = 0;
        }

        if (empty($product->code)) {
            $product->code = Functions::generateCode(10, true, 'products');
        }
    }

    /**
     * Handle the product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        // move file from tmp to product directory
        // and update image path
        if (Str::contains($product->image, $product::$fileDirectoryPrefixTmp)) {

            $fromPath = $product->image;
            $toPath = (
                $product::$fileDirectoryPrefix.'/'.
                $product->id.
                Str::after($product->image, $product::$fileDirectoryPrefixTmp)
            );

            Storage::disk($product::$disk)->move($fromPath, $toPath);

            $product->update(['image' => $toPath]);
        }

        // create a chat room for the product
        $product->createChatRoom();
    }

    /**
     * Handle the product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
