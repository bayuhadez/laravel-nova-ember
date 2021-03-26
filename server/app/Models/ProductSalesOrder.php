<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSalesOrder extends Model
{
    protected $table = 'product_salesorder';

    protected $fillable = [
        'product_id',
        'sales_order_id',
        'stock_division_id',
        'discount',
        'order_quantity',
        'sell_price',
        'sub_total',
        'status',
        'amount_approved',
        'amount_rejected',
        'amount_prepared',
        'amount_returned',
    ];

    const STATUS_UNAUTHORIZED = 0;
    const STATUS_PARTIALLY_AUTHORIZED = 1;
    const STATUS_AUTHORIZED = 2;
    const STATUS_PREPARED = 3;
    const STATUS_ON_DELIVERY = 4;
    const STATUS_DELIVERED = 5;
    const STATUS_BILL = 6;

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function salesOrder()
    {
        return $this->belongsTo('App\Models\SalesOrder', 'sales_order_id');
    }

    public function stockDivision()
    {
        return $this->belongsTo('App\Models\StockDivision', 'stock_division_id');
    }

    public function productStockMovements()
    {
        return $this->hasMany('App\Models\ProductStockMovement', 'product_salesorder_id');
    }

    /**
     * Indicate this product in SO used in product transaction receipt
     */
    public function productTransactionReceipt()
    {
        return $this->hasOne('App\Models\ProductTransactionReceipt', 'product_salesorder_id');
    }

    /**
     * Filter out PSO that have been used in transaction receipts other than the specified transaction receipt
     */
    public function scopeNotInOtherTransactionReceipt($q, $transactionReceiptId = null)
    {
        return $q->whereDoesntHave('productTransactionReceipt', function ($q) use ($transactionReceiptId) {
            $q->whereHas('transactionReceipt', function ($q) use ($transactionReceiptId) {
                $q->where('id', '!=', $transactionReceiptId);
            });
        });
    }

    public function scopeInSalesOrder($q, $salesOrderId)
    {
        return $q->where('sales_order_id', $salesOrderId);
    }

    /**
     * Filter out PSO that have different SO status from the specified status
     */
    public function scopeInSalesOrderWithStatus($q, $status = null)
    {
        return $q->whereHas('salesOrder', function ($q) use ($status) {
            $q->where('status', $status);
        });
    }

    /**
     * calculates sub_total
     *
     * @return float
     */
    public function calculateSubTotal()
    {
        return (
            ($this->order_quantity * $this->sell_price)
            - $this->discount
        );
    }

    public function getAmountLeftToPrepareAttribute()
    {
        $totalPrepared = 0;

        foreach ($this->productStockMovements as $psm) {
            if ($psm->in_or_out == 1) {
                $totalPrepared += $psm->quantity;
            }
        }

        return $this->amount_approved - $totalPrepared;
    }

    /**
     * based on this pso's status, returns its text representation
     *
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        switch ($this->status) {
            case self::STATUS_UNAUTHORIZED:
                $text = __('Belum Otorisasi'); break;
            case self::STATUS_PARTIALLY_AUTHORIZED:
                $text = __('Otorisasi Sebagian'); break;
            case self::STATUS_AUTHORIZED:
                $text = __('Sudah Otorisasi'); break;
            case self::STATUS_PREPARED:
                $text = __('Sudah Siap'); break;
            case self::STATUS_ON_DELIVERY:
                $text = __('Mengirim'); break;
            case self::STATUS_DELIVERED:
                $text = __('Terkirim'); break;
            case self::STATUS_BILL:
                $text = __('Siap Buat Nota'); break;
        }

        return $text;
    }

    /**
     * Scope for datatable column filters
     */
    public function scopeSalesOrderCompanyNameLike($q, $term)
    {
        $q->whereHas('salesOrder', function ($q) use ($term) {
            $q->whereHas('company', function ($q) use ($term) {
                $q->where('name', 'like', '%'.$term.'%');
            });
        });
    }

    public function scopeSalesOrderCompanyDivisionFilter($q, $term)
    {
        $q->whereHas('salesOrder', function ($q) use ($term) {
            $q->whereHas('company', function ($q) use ($term) {
                $q->where('division', $term);
            });
        });
    }

    public function scopeSalesOrderRequiresDeliveryFilter($q, $term)
    {
        $q->whereHas('salesOrder', function ($q) use ($term) {
            $q->where('requires_delivery', $term);
        });
    }

    public function scopeSalesOrderNumberLike($q, $term)
    {
        $q->whereHas('salesOrder', function ($q) use ($term) {
            $q->where('number', 'like', '%'.$term.'%');
        });
    }

    public function scopeSalesOrderCustomerPersonNameLike($q, $term)
    {
        $q->whereHas('salesOrder', function ($q) use ($term) {
            $q->whereHas('customer', function ($q) use ($term) {
                $q->whereHas('person', function ($q) use ($term) {
                    $q->where('name', 'like', '%'.$term.'%');
                });
            });
        });
    }

    public function scopeSalesOrderDeliveryRecipientNameLike($q, $term)
    {
        $q->whereHas('salesOrder', function ($q) use ($term) {
            $q->whereHas('deliveryRecipientCustomer', function ($q) use ($term) {
                $q->nameLike($term);
            });
        });
    }

    public function scopeSalesOrderDeliveryAddressLike($q, $term)
    {
        $q->whereHas('salesOrder', function ($q) use ($term) {
            $q->whereHas('deliveryAddress', function ($q) use ($term) {
                $q->where('address', 'like', '%'.$term.'%');
            });
        });
    }

    public function scopeSalesOrderDeliveryAddressRegencyLike($q, $term)
    {
        $q->whereHas('salesOrder', function ($q) use ($term) {
            $q->whereHas('deliveryAddress', function ($q) use ($term) {
                $q->whereHas('regency', function ($q) use ($term) {
                    $q->where('name', 'like', '%'.$term.'%');
                });
            });
        });
    }

    public function scopeProductNameLike($q, $term)
    {
        $q->whereHas('product', function ($q) use ($term) {
            $q->where('name', 'like', '%'.$term.'%');
        });
    }

    public function scopeStockDivisionNameLike($q, $term)
    {
        $q->whereHas('stockDivision', function ($q) use ($term) {
            $q->where('name', 'like', '%'.$term.'%');
        });
    }

    public function scopeStatusFilter($q, $term)
    {
        $q->where('status', $term);
    }

    public function scopeInSalesOrders($q, array $salesOrderIds)
    {
        return $q->whereIn('product_salesorder.sales_order_id', $salesOrderIds);
    }
}
