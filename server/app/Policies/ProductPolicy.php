<?php

namespace App\Policies;

use App\Interfaces\ApprovableInterface;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function browse(User $user)
    {
        return $user->isAbleTo('product.browse');
    }

    /**
     * Determine whether the user can view any posts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function read(User $user, Product $product)
    {
        return $user->isAbleTo('product.read');
    }

    /**
     * Determine whether the user can add products.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function add(User $user)
    {
        return $user->isAbleTo('product.add');
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function edit(User $user, Product $product)
    {
        return $user->isAbleTo('product.edit');
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function delete(User $user, Product $product)
    {
        return $user->isAbleTo('product.delete');
    }

    /**
     * Determine whether the user can restore the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function restore(User $user, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function forceDelete(User $user, Product $product)
    {
        //
    }

    public function buy(User $user, Product $product)
    {
        $beforeEndTime = true;
        $seminarProductMeta = $product->seminarProductMeta;

        if (!is_null($seminarProductMeta)) {

            $now = Carbon::now()->tz('UTC');

            $endTime = $seminarProductMeta->end_time;

            $beforeEndTime = $endTime->lt($now);
        }

        return (
            $product->status == ApprovableInterface::STATUS_APPROVED &&
            $beforeEndTime
        );
    }

}
