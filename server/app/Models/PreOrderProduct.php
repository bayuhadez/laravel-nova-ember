<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreOrderProduct extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    protected $fillable = [
        'quantity',
        'purchase_price',
        'purchase_price_per_pcs',
        'purchase_price_foreign',
        'purchase_price_foreign_per_pcs',
        'discounts',
        'sub_total',
        'cost',
    ];

    /**
     * Get the requestOrder that owns the requestOrderProduct.
     */
    public function preOrder()
    {
        return $this->belongsTo('App\Models\PreOrder');
    }

    /**
     * Get the unit
     */
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * Get the productUnit
     */
    public function productUnit()
    {
        return $this->belongsTo('App\Models\ProductUnit');
    }

    /**
     * Scope a query to only include items from specifict pre order.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param integer $preOrderId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInPreOrders($q, $preOrderIds = [])
    {
        return $q->whereIn('pre_order_id', $preOrderIds);
    }
}
