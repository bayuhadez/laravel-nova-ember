<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestOrderProduct extends Model
{
    use SoftDeletes;

    protected $table = 'request_order_products';

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

    protected $fillable = [
        'information',
        'quantity',
    ];

    /**
     * Get the requestOrder that owns the requestOrderProduct.
     */
    public function requestOrder()
    {
        return $this->belongsTo('App\Models\RequestOrder');
    }

    /**
     * Get the product that owns the requestOrderProduct.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * Get the unit that owns the requestOrderProduct.
     */
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function productUnit()
    {
        return $this->belongsTo('App\Models\ProductUnit');
    }

    /**
     * Scope a query to only include items from specifict requestOrder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param integer $requestOrderId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInRequestOrder($q, $requestOrderId)
    {
        return $q->where('request_order_id', $requestOrderId);
    }
}
