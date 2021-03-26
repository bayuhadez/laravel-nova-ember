<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReceiptProduct extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    /**
     * Get the company that owns the billItem.
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * Get the bill that owns the billItem.
     */
    public function purchaseReceipt()
    {
        return $this->belongsTo('App\Models\PurchaseReceipt');
    }

    /**
     * Get the product that owns the billItem.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * Get the warehouse that owns the billItem.
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse');
    }

    /**
     * Get the rack that owns the billItem.
     */
    public function rack()
    {
        return $this->belongsTo('App\Models\Rack');
    }

}
