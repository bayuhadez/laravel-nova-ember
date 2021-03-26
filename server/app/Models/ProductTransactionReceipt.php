<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTransactionReceipt extends Model
{
    use SoftDeletes;

    protected $table = 'product_transactionreceipt';

    protected $fillable = [
        'quantity',
        'price',
        'price_per_pcs',
        'foreign_price',
        'foreign_price_per_pcs',
        'discounts',
        'sub_total',
        'cost',
        'total',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /** ---------- Relationships ----------*/

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function productUnit()
    {
        return $this->belongsTo('App\Models\ProductUnit');
    }

    public function transactionReceipt()
    {
        return $this->belongsTo('App\Models\TransactionReceipt');
    }

    public function preOrderProduct()
    {
        return $this->belongsTo('App\Models\PreOrderProduct');
    }

    public function productSalesOrder()
    {
        return $this->belongsTo('App\Models\ProductSalesOrder', 'product_salesorder_id');
    }

    public function productStockMovements()
    {
        return $this->hasMany(
            'App\Models\ProductStockMovement',
            'product_transactionreceipt_id'
        );
    }

    /** ---------- Scopes ---------- */
    public function scopeInTransactionReceipts($q, $transactionReceiptIds = [])
    {
        return $q->whereIn('transaction_receipt_id', $transactionReceiptIds);
    }

    /** ---------- Accessors ---------- */
    /**
     * Return related purchaseReceipt
     * @return PurchaseReceipt|null
     */
    public function getPurchaseReceiptAttribute()
    {
        return $this->transactionReceipt->purchaseReceipt;
    }

}
