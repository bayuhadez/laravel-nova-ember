<?php

namespace App\Models;

use App\Lib\Functions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReceipt extends Model
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
        'due_at',
        'receipted_at',
        'updated_at',
        'delivery_order_at',
        'receipt_letter_at',
    ];

    protected $fillable = [
        'currency_rate',
        'discounts',
        'due_at',
        'is_ppn',
        'number',
        'receipted_at',
        'rounded_total',
        'rounding_type',
        'rounding_value',
        'son_number',
        'sub_total',
        'total',
        'delivery_order_number',
        'tax_invoice_number',
        'delivery_order_at',
        'receipt_letter_at',
    ];

    /**
     * Get the company that owns the purchaseReceipt.
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * Get the user creator that owns the pre order.
     */
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    /**
     * Get the currency that for pre order.
     */
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    /**
     * Get the transactionReceipt that owns the purchaseReceipt
     */
    public function transactionReceipt()
    {
        return $this->belongsTo('App\Models\TransactionReceipt');
    }

    public function preOrders()
    {
        return $this
            ->belongsToMany('App\Models\PreOrder', 'preorder_purchasereceipt')
            ->using('App\Models\PreOrderPurchaseReceipt')
            ->withTimestamps();
    }

    /** ---------- Accessors ---------- */
    /**
     * Return related purchaseReceipt
     * @return PurchaseReceipt|null
     */
    public function getProductTransactionReceiptsAttribute()
    {
        return $this->transactionReceipt->productTransactionReceipts ?? null;
    }

    /** ---------- Methods ---------- */
    public function sumSubTotalFromRelatedProducts()
    {
        $this->transactionReceipt->load([
            'productTransactionReceipts' => function ($q) {
                $q->select(['id', 'transaction_receipt_id', 'total']);
            }
        ]);

        return $this->productTransactionReceipts->sum('total');
    }

    public function calculateGrandTotal(): float
    {
        $subTotal = $this->sumSubTotalFromRelatedProducts();

        $discount = Functions::countDiscountFromNumber($this->discounts, $subTotal);

        $ppn = 0;
        if ($this->is_ppn) {
            $ppn = Functions::countPpnFromNumber($subTotal);
        }

        $subTotal = $subTotal - $discount;

        $total = $subTotal + $ppn;

        if ($total <= 0) {
            return 0;
        }

        return $total;
    }

    /**
     * Set sub_total and total
     * and run save() at the end
     * @return bool
     */
    public function updateSubTotalAndTotal()
    {
        $this->sub_total = $this->sumSubTotalFromRelatedProducts();
        $this->total = $this->calculateGrandTotal();

        return $this->save();
    }

}
