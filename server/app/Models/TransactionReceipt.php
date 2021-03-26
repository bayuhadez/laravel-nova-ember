<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionReceipt extends Model
{
    protected $fillable = [
        'supplier_id',
        'customer_id'
    ];

    /**
     * Get the customer that owns the phone.
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    /**
     * Get the customer that owns the phone.
     */
    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier');
    }

    public function purchaseReceipt()
    {
        return $this->hasOne('App\Models\PurchaseReceipt');
    }
    
    public function salesReceipt()
    {
        return $this->hasOne('App\Models\SalesReceipt');
    }

    /**
     * Get the productTransactionReceipts for the transactionReceipt
     */
    public function productTransactionReceipts()
    {
        return $this->hasMany('App\Models\ProductTransactionReceipt');
    }

    /**
     * Get the serviceTransactionReceipts for the transactionReceipt
     */
    public function serviceTransactionReceipts()
    {
        return $this->hasMany('App\Models\ServiceTransactionReceipt');
    }

}
