<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSalesReceipt extends Model
{
    protected $table = 'product_salesreceipt';

    protected $fillable = [
        'product_id',
        'sales_receipt_id',
        'sales_order_id',
        'order_quantity',
        'sell_price',
        'total',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function productSalesOrder()
    {
        return $this->belongsTo('App\Models\ProductSalesOrder', 'product_salesorder_id');
    }

    public function salesReceipt()
    {
        return $this->belongsTo('App\Models\SalesReceipt', 'sales_receipt_id');
    }

    public function scopeInSalesReceipt($q, $salesReceiptId)
    {
        return $q->where('sales_receipt_id', $salesReceiptId);
    }
}
