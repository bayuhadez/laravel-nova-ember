<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderService extends Model
{
    protected $table = 'salesorder_service';

    protected $fillable = [
        'service_id',
        'sales_order_id',
        'discount',
        'order_quantity',
        'sell_price',
    ];

    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id');
    }

    public function salesOrder()
    {
        return $this->belongsTo('App\Models\SalesOrder', 'sales_order_id');
    }

    /**
     * Indicate this service in SO being used in service transaction receipt
     */
    public function serviceTransactionReceipt()
    {
        return $this->hasOne('App\Models\ServiceTransactionReceipt', 'salesorder_service_id');
    }

    /**
     * Filter out SOS that have been used in transaction receipts other than the specified transaction receipt
     */
    public function scopeNotInOtherTransactionReceipt($q, $transactionReceiptId = null)
    {
        return $q->whereDoesntHave('salesReceiptService', function ($q) use ($transactionReceiptId) {
            $q->whereHas('salesReceipt', function ($q) use ($transactionReceiptId) {
                $q->where('id', '!=', $transactionReceiptId);
            });
        });
    }

    public function scopeInSalesOrders($q, array $salesOrderIds)
    {
        return $q->whereIn('salesorder_service.sales_order_id', $salesOrderIds);
    }

    /**
     * Filter out SOS that have different SO status from the specified status
     */
    public function scopeInSalesOrderWithStatus($q, $status = null)
    {
        return $q->whereHas('salesOrder', function ($q) use ($status) {
            $q->where('status', $status);
        });
    }

    /**
     * calculates total
     *
     * @return float
     */
    public function calculateTotal()
    {
        return (
            ($this->order_quantity * $this->sell_price)
            - $this->discount
        );
    }
}
