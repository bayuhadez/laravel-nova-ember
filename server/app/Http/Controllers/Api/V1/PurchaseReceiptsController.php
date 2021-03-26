<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\PurchaseReceipt;
use CloudCreativity\LaravelJsonApi\Document\Error;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
use CloudCreativity\LaravelJsonApi\Http\Requests\FetchResource;
use Illuminate\Http\Response;

class PurchaseReceiptsController extends JsonApiController
{
    /* Custom Actions -------------------------- */
    /**
     * Create Order and OrderDetails
     * @param FetchResource $request
     * @param PurchaseReceipt $purchaseReceipt
     *
     * @return Json Order resource (jsonapi format)
     */
    public function updateCalculation(
        FetchResource $request,
        PurchaseReceipt $purchaseReceipt
    ): Response {
        $purchaseReceipt->updateSubTotalAndTotal();

        return $this->reply()->content($purchaseReceipt);
    }
}
