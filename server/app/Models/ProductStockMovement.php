<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStockMovement extends BaseModel
{
    const OUT = 0;
    const IN  = 1;

    use SoftDeletes;

    protected $fillable = [
        'datetime',
        'in_or_out',
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

    /** Relationships : **/

    public function company()
    {
        return $this->hasOneThrough('App\Models\Company', 'App\Models\StockDivision');
    }

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

    public function productSalesOrder()
    {
        return $this->belongsTo('App\Models\ProductSalesOrder', 'product_salesorder_id');
    }

    public function productStock()
    {
        return $this->belongsTo('App\Models\ProductStock');
    }

    public function productTransactionReceipt()
    {
        return $this->belongsTo('App\Models\ProductTransactionReceipt', 'product_transactionreceipt_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    /** ---------- Scopes ---------- */
    /**
     * Scope a query to only include productStockMovements which are related with productTransactionReceipt
     *
     * @param  \Illuminate\Database\Eloquent\Builder $q
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInProductTransactionReceipts($q, $productTransactionReceiptIds = [])
    {
        return $q->whereIn(
            'product_transactionreceipt_id',
            $productTransactionReceiptIds
        );
    }

    public function scopeSortFifoByProduct($q, $productId = null)
    {
        return $q->where('product_id', '=', $productId)
            ->where('quantity', '>', 0)
            ->orderBy('datetime', 'ASC');
    }

    public function scopeInProduct($q, $productId)
    {
        return $q->where('product_stock_movements.product_id', '=', $productId);
    }

    public function scopeInStockDivision($q, $stockDivisionId)
    {
        return $q->where('product_stock_movements.stock_division_id', '=', $stockDivisionId);
    }

    public function getStockAttribute()
    {
        if ($this->in_or_out == self::IN) {
            // only show stock for psm type IN
            return $this->productStock->quantity ?? null;
        }
        return null;
    }

    public function getPurchaseReceiptAttribute()
    {
        return $this->productTransactionReceipt->transactionReceipt->purchaseReceipt ?? null;
    }

    public function getSalesReceiptAttribute()
    {
        return $this->productTransactionReceipt->transactionReceipt->salesReceipt ?? null;
    }

    public function scopeForProductStock($q, $productStockId)
    {
        $productStock = ProductStock::find($productStockId);

        return $q->whereDatetime($productStock->datetime)
            ->whereProductId($productStock->product_id)
            ->whereRackId($productStock->rack_id)
            ->whereStockDivisionId($productStock->stock_division_id);
    }

    /** ---------- Accessors ---------- */
    /**
     * Get related transactionReceipt
     * @return TransactionReceipt|null
     */
    public function getTransactionReceiptAttribute()
    {
        return $this->productTransactionReceipt->transactionReceipt ?? null;
    }

    public function getPriceAttribute()
    {
        // this is hack only for demo purpose, please delete this block later [
        if (!empty($this->productSalesOrder)) {
            return $this->productSalesOrder->productSalesReceipt->total ?? null;
        }
        // ]

        return $this->productTransactionReceipt->total ?? null;
    }

    public function getReceiptNumberAttribute()
    {
        $transactionReceipt = $this->transactionReceipt;
        if (!empty($transactionReceipt)) {
            if (!empty($transactionReceipt->salesReceipt)) {
                return $transactionReceipt->salesReceipt->number;
            } else if (!empty($transactionReceipt->purchaseReceipt)) {
                return $transactionReceipt->purchaseReceipt->number;
            }
        }

        // this is hack only for demo purpose, please delete this block later [
        if (!empty($this->customer)) {
            return $this->productSalesOrder->productSalesReceipt->salesReceipt->number ?? null;
        }
        // ]
        return null;
    }

    public function getFromAttribute()
    {
        if ($this->in_or_out == 1) {
            if (!empty($this->purchaseReceipt)) {
                return $this->transactionReceipt->supplier->company->name ?? null;
            } else {
                if (!empty($this->rack)) {
                    return $this->rack->warehouse->name . ' - ' . $this->rack->name;
                }
            }
        }

        return null;
    }

    public function getToAttribute()
    {
        if ($this->in_or_out == 0) {
            if (!empty($this->customer)) {
                return $this->customer->displayName ?? null;
            } else {
                if (!empty($this->rack)) {
                    return $this->rack->warehouse->name . ' - ' . $this->rack->name;
                }
            }
        }

        return null;
    }
}
