<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProduct extends Model
{
    protected $table = 'company_product';

    protected $fillable = [
        'point',
        'price'
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /** ---------- Accessors ---------- */
    /**
     * Get price from latest related product in purchase receipt
     */
    public function getReceiptPriceAttribute()
    {
        $latestProductTransactionReceipt = 
            $this->product->productTransactionReceipts
                ->where('transactionReceipt.purchaseReceipt.company.id', $this->company_id)
                ->sortBy('updated_at')
                ->last();
        
        if (empty($latestProductTransactionReceipt)) {
            return null;
        } else {
            return $latestProductTransactionReceipt->price_per_pcs;
        }
    }
}
