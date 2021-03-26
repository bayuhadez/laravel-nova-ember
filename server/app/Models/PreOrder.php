<?php

namespace App\Models;

use App\Interfaces\RoundableInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreOrder extends Model implements RoundableInterface
{
    use SoftDeletes;

    const STATUS_NOT_DONE = 0;
    const STATUS_DONE = 1;
    const STATUS_DRAFT = 2;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at',
        'ordered_at',
        'due_at',
    ];

    protected $fillable = [
        'currency_rate',
        'discounts',
        'division',
        'due_at',
        'is_ppn',
        'number',
        'ordered_at',
        'rounded_total',
        'rounding_type',
        'rounding_value',
        'son_number',
        'status',
        'total',
        'grand_total',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_ppn' => 'boolean',
        'discounts' => 'array',
        //'ordered_at' => 'date',
        //'due_at' => 'datetime:d/m/Y',
    ];

    /**
     * Get the company that owns the pre order.
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
     * Get the supplier that owns the pre order.
     */
    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier');
    }

    /**
     * The users that belong to the role.
     */
    public function requestOrders()
    {
        return $this->hasMany('App\Models\RequestOrder');
    }

    /**
     * Get the preOrderProducts for the pre order
     */
    public function preOrderProducts()
    {
        return $this->hasMany('App\Models\PreOrderProduct');
    }

    /**
     * Get the purchaseReceipts for the preOrder
     */
    public function purchaseReceipts()
    {
        return $this
            ->belongsToMany('App\Models\PurchaseReceipt', 'preorder_purchasereceipt')
            ->using('App\Models\PreOrderPurchaseReceipt')
            ->withTimestamps();
    }

    /** scopes: **/
    /**
     * Scope a query to only include items that are related with specifict pre order.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $preOrderIds
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInPurchaseReceipts($q, $purchaseReceiptIds = [])
    {
        return $q->whereHas('purchaseReceipts', function ($q) use ($purchaseReceiptIds) {
            $q->where($this->table.'.purchase_receipt_id', $purchaseReceiptIds);
        });
    }

    /**
     * Scope a query to specify preOrder that doesn't have relation with any purchaseReceipt
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $purchaseReceipt
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDoesntHavePurchaseReceipts($q)
    {
        return $q->whereDoesntHave('purchaseReceipts');
    }

    /**
     * Scope for datatable column filters
     */
    public function scopeCompanyNameLike($q, $term)
    {
        $q->whereHas('company', function ($q) use ($term) {
            $q->where('name', 'like', '%'.$term.'%');
        });
    }

    public function scopeCompanyDivisionFilter($q, $term)
    {
        $q->whereHas('company', function ($q) use ($term) {
            $q->where('division', $term);
        });
    }

    public function scopeSupplierCompanyNameLike($q, $term)
    {
        $q->whereHas('supplier', function ($q) use ($term) {
            $q->whereHas('company', function ($q) use ($term) {
                $q->where('name', 'like', '%'.$term.'%');
            });
        });
    }

    public function scopeCreatedByNameLike($q, $term)
    {
        $q->whereHas('createdBy', function ($q) use ($term) {
            $q->where('name', 'like', '%'.$term.'%');
        });
    }

    public function scopeStatusFilter($q, $term)
    {
        $q->where('status', $term);
    }

    public function scopeNumberLike($q, $term)
    {
        $q->where('number', 'like', '%'.$term.'%');
    }

    public function scopeTotalLike($q, $term)
    {
        $q->where('total', 'like', '%'.$term.'%');
    }

    public function scopeNotIn($q, $ids)
    {
        return $q->whereNotIn('id', $ids);
    }

}
