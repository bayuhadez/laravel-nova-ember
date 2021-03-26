<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReceiptService extends Model
{
    protected $table = 'salesreceipt_service';

    protected $fillable = [
        'service_id',
        'sales_receipt_id',
        'sales_order_id',
        'order_quantity',
        'sell_price',
        'total',
    ];

    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id');
    }

    public function salesOrderService()
    {
        return $this->belongsTo('App\Models\SalesOrderService', 'salesorder_service_id');
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
