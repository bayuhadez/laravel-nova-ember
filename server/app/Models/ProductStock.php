<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStock extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'datetime',
        'quantity',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'datetime',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    /** relationships **/
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function rack()
    {
        return $this->belongsTo('App\Models\Rack');
    }

    public function stockDivision()
    {
        return $this->belongsTo('App\Models\StockDivision');
    }

    public function productStockMovements()
    {
        return $this->hasMany('App\Models\StockDivision');
    }

    /**
     * Sort available product stocks of given product by asc datetime
     */
    public function scopeSortFifoByProduct($q, $productId = null)
    {
        return $q->where('product_id', '=', $productId)
            ->where('quantity', '>', 0)
            ->orderBy('datetime', 'ASC');
    }

    /**
     * Sort existing product stocks of given product by desc datetime
     */
    public function scopeSortFifoByProductOut($q, $productId = null)
    {
        return $q->where('product_id', '=', $productId)
            ->orderBy('datetime', 'DESC');
    }

    public function scopeInProduct($q, $productId = null)
    {
        return $q->where('product_id', '=', $productId);
    }
    
    public function scopeInRack($q, $rackId = null)
    {
        return $q->where('rack_id', '=', $rackId);
    }

    public function scopeInStockDivision($q, $stockDivisionId = null)
    {
        return $q->where('stock_division_id', '=', $stockDivisionId);
    }

}
