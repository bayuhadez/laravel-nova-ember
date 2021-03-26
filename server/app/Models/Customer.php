<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'customer_id',
        'person_id',
        'pic_id',
        'code',
        'email',
        'is_sub_customer',
    ];

    protected $guarded = ['company_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function parentCustomer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function childrenCustomer()
    {
        return $this->hasMany('App\Models\Customer', 'customer_id');
    }

    public function person()
    {
        return $this->belongsTo('App\Models\Person', 'person_id');
    }

    public function pic()
    {
        return $this->belongsTo('App\Models\Person', 'pic_id');
    }

    public function companyCustomers()
    {
        return $this->hasMany('App\Models\CompanyCustomer', 'customer_id');
    }

    public function productStockMovements()
    {
        return $this->hasMany('App\Models\ProductStockMovement');
    }

    /**
     * Get the salesOrders for the customer.
     */
    public function salesOrders()
    {
        return $this->hasMany('App\Models\SalesOrder');
    }

    /**
     * Get the transactionReceipts for the customer.
     */
    public function transactionReceipts()
    {
        return $this->hasMany('App\Models\TransactionReceipt');
    }

    /** Accessor **/
    public function getDisplayNameAttribute()
    {
        if (!empty($this->company)) {

            if (!empty($this->person)) {
                return $this->company->name . ' - ' . $this->person->fullname;
            } else {
                return $this->company->name;
            }
        }
        return $this->person->fullname;
    }

    /** scopes: **/
    /**
     * Scope a query to only include customers that are related with SO(s) with READY status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInReadySO($q)
    {
        $q->whereHas('salesOrders', function ($q) {
            $q->where('status', SalesOrder::STATUS_READY);
        });
    }

    public function scopeAssignedInSalesOrderByCompany($q, $companyId)
    {
        return $q->whereHas('salesOrders', function ($q) use ($companyId) {
            $q->where('sales_orders.company_id', '=', $companyId);
        });
    }

    public function scopeNameLike($q, $term)
    {
        $q->where(function ($q) use ($term) {
            $q->whereHas('company', function ($q) use ($term) {
                $q->where('name', 'like', '%'.$term.'%');
            })->orWhereHas('person', function ($q) use ($term) {
                $q->where('first_name', 'like', '%'.$term.'%')
                ->orWhere('last_name', 'like', '%'.$term.'%');
            });
        });
    }
}
