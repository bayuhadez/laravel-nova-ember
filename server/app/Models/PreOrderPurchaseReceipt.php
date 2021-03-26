<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PreorderPurchasereceipt extends Pivot
{
    protected $table = 'preorder_purchasereceipt';

    public $incrementing = true;

    protected $fillable = [
        'pre_order_id',
        'purchase_receipt_id',
    ];

    /**
     * Get the preOrder that owns the preOrderPurchaseReceipt
     */
    public function preOrder()
    {
        return $this->belongsTo('App\Models\PreOrder');
    }

    /**
     * Get the purchaseReceipt that owns the preOrderPurchaseReceipt
     */
    public function purchaseReceipt()
    {
        return $this->belongsTo('App\Models\PurchaseReceipt');
    }
}
