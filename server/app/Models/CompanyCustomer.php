<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyCustomer extends Pivot
{
    protected $table = 'company_customer';

    public $incrementing = true;

    protected $fillable = [
        'ppn',
        'company_id',
        'customer_id',
        'credit_limit',
        'term_of_payment',
    ];

    /**
     * Get the company that owns the companyCustomer
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * Get the customer that owns the companyCustomer
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    /**
     * Get the staffs that act as seller of the companyCustomer
     */
    public function staffs()
    {
        return $this->belongsToMany(
            'App\Models\Staff',
            'companycustomer_staff',
            'company_customer_id',
            'staff_id'
        );
    }

    public function scopeInCustomer($q, $customerId)
    {
        return $q->where('company_customer.customer_id', '=', $customerId);
    }

    public function scopeInCompany($q, $companyId)
    {
        return $q->where('company_customer.company_id', '=', $companyId);
    }
}
