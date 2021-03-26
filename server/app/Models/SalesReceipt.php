<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Company;

class SalesReceipt extends Model
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
        'updated_at',
        'receipted_at',
    ];

    protected $fillable = [
        'number',
        'is_ppn',
        'total',
        'sub_total',
    ];

    /**
     * Get the company that owns the salesReceipt.
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * Get the user creator that owns the sales.
     */
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    /**
     * Get the address of customer for the salesReceipt.
     */
    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }

    /**
     * Get the transactionReceipt that owns the salesReceipt
     */
    public function transactionReceipt()
    {
        return $this->belongsTo('App\Models\TransactionReceipt', 'transaction_receipt_id');
    }

    public function scopeInCompanyAndDescendant($q, $companyId)
    {
        $company = Company::find($companyId);
        $companyIds = $company->childrenCompany->pluck('id')->all();
        $companyIds[] = $company->id;

        return $q->whereHas('company', function ($q) use ($companyIds) {
            $q->whereIn('companies.id', $companyIds);
        });
    }

    // accessors:
    /**
     * Accessor returns all sales' names in related PSR and SRS
     *
     */
    public function getSalesNamesAttribute()
    {
        return $this->salesOrders
            ->pluck('salesName')
            ->unique()
            ->implode(', ');
    }

    /**
     * @return Collection of SalesOrder instances related with this SalesReceipt
     * from its related productSalesReceipts and salesReceiptServices
     */
    public function getSalesOrdersAttribute()
    {
        // buffer related sales orders
        $salesOrders = [];

        // put salesOrders from related productSalesReceipts into buffer
        foreach ($this->transactionReceipt->productTransactionReceipts as $ptr) {
            if (!empty($ptr->productSalesOrder)) {
                $salesOrders[] = $ptr->productSalesOrder->salesOrder;
            }
        }

        // put salesOrders from related salesReceiptServices into buffer
        foreach ($this->transactionReceipt->serviceTransactionReceipts as $str) {
            if (!empty($str->salesOrderService)) {
                $salesOrders[] = $str->salesOrderService->salesOrder;
            }
        }

        return collect($salesOrders);
    }

}
