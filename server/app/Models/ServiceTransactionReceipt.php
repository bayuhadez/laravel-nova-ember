<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTransactionReceipt extends Model
{
    protected $table = 'service_transactionreceipt';

    protected $fillable = [
        'service_id',
        'transaction_receipt_id',
        'salesorder_service_id',
        'quantity',
        'price',
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

    public function transactionReceipt()
    {
        return $this->belongsTo('App\Models\TransactionReceipt', 'transaction_receipt_id');
    }

}
