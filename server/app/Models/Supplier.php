<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'code',
        'pic',
        'top',
        'plafon',
        'accounting_number',
        'information',
        'currency_id',
        'supplier_category_id',
    ];

    public function supplierCategory()
    {
        return $this->belongsTo('App\Models\SupplierCategory');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * Get the pic that the supplier belongs to.
     */
    public function pic()
    {
        return $this->belongsTo('App\Models\Person', 'pic_id');
    }

    /**
     * Get the transactionReceipts for the supplier.
     */
    public function transactionReceipts()
    {
        return $this->hasMany('App\Models\TransactionReceipt');
    }

    /** Scopes **/

    public function scopeCompanyNameLike($q, $term)
    {
        return $q->whereHas('company', function ($q) use ($term) {
            $q->nameLike($term);
        });
    }

    public function scopeCurrencyCode($q, $term)
    {
        return $q->whereHas('currency', function ($q) use ($term) {
            $q->code($term);
        });
    }
}
