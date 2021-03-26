<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'division',
        'code',
        'telephone_number',
        'mobile_number',
        'fax_number',
        'value_added_tax_number',
        'value_added_tax_type',
        'created_by',
        'updated_by',
    ];

    const VALUE_ADDED_TAX_TYPE_MANUAL = 1;
    const VALUE_ADDED_TAX_TYPE_YES = 2;
    const VALUE_ADDED_TAX_TYPE_NO = 3;

    const DIVISION_NONE = 0;
    const DIVISION_WHOLESALE = 1;
    const DIVISION_RETAIL = 2;

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function companyWarehouses()
    {
        return $this->hasMany('App\Models\CompanyWarehouse', 'company_id');
    }

    public function customers()
    {
        return $this->hasMany('App\Models\Customer');
    }

	public function owners()
	{
		return $this->belongsToMany('App\Models\User');
	}

    public function products()
    {
        return $this->belongsToMany(
            'App\Models\Product',
            'company_product',
            'company_id',
            'product_id'
        );
    }

    public function services()
    {
        return $this->belongsToMany('App\Models\Service');
    }

	public function productCategories()
	{
		return $this->hasMany('App\Models\ProductCategory');
	}

	public function banners()
	{
		return $this->hasMany('App\Models\Banner');
	}

	public function vouchers()
	{
		return $this->hasMany('App\Models\Voucher');
	}

    public function parentCompany()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function childrenCompany()
    {
        return $this->hasMany('App\Models\Company', 'company_id');
    }

    public function staffs()
    {
        return $this->hasMany('App\Models\Staff');
    }

    public function stockDivisions()
    {
        return $this->hasMany('App\Models\StockDivision');
    }

    public function warehouses()
    {
        return $this->belongsToMany(
            'App\Models\Warehouse',
            'company_warehouse',
            'company_id',
            'warehouse_id'
        );
    }
    
    public function addresses()
    {
        return $this->belongsToMany(
            'App\Models\Address',
            'company_address',
            'company_id',
            'address_id'
        )->withPivot('is_default');
    }
    
    public function companyAddresses()
    {
        return $this->hasMany('App\Models\CompanyAddress', 'company_id');
    }
    
    public function companyCustomers()
    {
        return $this->hasMany('App\Models\CompanyCustomer', 'company_id');
    }

    public function getDivisionNameAttribute()
    {
        if ($this->division == self::DIVISION_NONE) {
            return 'None';
        } else if ($this->division == self::DIVISION_WHOLESALE) {
            return 'Wholesale';
        } else if ($this->division == self::DIVISION_RETAIL) {
            return 'Retail';
        }
    }

    public function scopeCodeExist($q, $code)
    {
        return $q->where('companies.code', '=', $code);
    }

    public function scopeChildInCompany($q, $parentCompanyId)
    {
        return $q->where('companies.company_id', '=', $parentCompanyId);
    }

    public function scopeMeAndChildren($q, $parentCompanyId)
    {
        return $q->where('companies.id', '=', $parentCompanyId)
            ->orWhere('companies.company_id', '=', $parentCompanyId);
    }

    /**
     * Scope returns companies and their children according to param
     *
     * @param array $parentCompanyId parent companies that will be searched for its children
     * 
     * @return Query
     */
    public function scopeWeAndChildren($q, array $parentCompanyIds)
    {
        return $q->whereIn('companies.id', $parentCompanyIds)
            ->orWhereIn('companies.company_id', $parentCompanyIds);
    }

    public function scopeInCompanies($q, array $companyIds)
    {
        return $q->whereIn('companies.id', $companyIds);
    }

    public function scopeNameLike($q, $term)
    {
        return $q->where("{$this->getTable()}.name", 'like', '%'.$term.'%');
    }

    /**
     * Scope returns companies record that used by customer records
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsedByCustomer($q)
    {
        return $q->has('customers');
    }

    /**
     * Scope returns companies that have not been used by a specific product as its CompanyProduct
     *
     * @return Query
     */
    public function scopeNotUsedByProduct($q, $productId)
    {
        $excludedCompanyIds = CompanyProduct::where('product_id', $productId)->get()->pluck('company_id')->all();
        return $q->whereNotIn('id', $excludedCompanyIds);
    }
    
    /**
     * Scope returns companies that have not been used by a specific service as its CompanyService
     *
     * @return Query
     */
    public function scopeNotUsedByService($q, $serviceId)
    {
        $excludedCompanyIds = CompanyService::where('service_id', $serviceId)->get()->pluck('company_id')->all();
        return $q->whereNotIn('id', $excludedCompanyIds);
    }

    /**
     * Scope returns companies that have not been used by a specific user as its CompanyUser
     *
     * @return Query
     */
    public function scopeNotUsedByUser($q, $userId)
    {
        $excludedCompanyIds = CompanyUser::where('user_id', $userId)->get()->pluck('company_id')->all();
        return $q->whereNotIn('id', $excludedCompanyIds);
    }

    /**
     * Scope returns companies that have not been used by a specific staff's person as its company
     *
     * @return Query
     */
    public function scopeNotUsedByStaffPerson($q, $personId)
    {
        return $q->whereDoesntHave('staffs', function ($q) use ($personId) {
            return $q->whereHas('person', function ($q) use ($personId) {
                return $q->where('id', $personId);
            });
        });
    }

    /**
     * Scope returns companies that not used by this companyCustomer
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotUsedByCompanyCustomer($q, $customerId)
    {
        return $q->whereDoesntHave('companyCustomers', function ($q) use ($customerId) {
            $q->where('company_customer.customer_id', '=', $customerId);
        });
    }

    // accessors:
    /**
     * Accessor returns default address or the first address
     *
     * @return Address|null
     */
    public function getDefaultOrFirstAddressAttribute()
    {
        if ($this->companyAddresses->isEmpty()) {
            return null;
        }

        $companyAddress = (
            $this->companyAddresses->firstWhere('is_default', true)
            ?? $this->companyAddresses->first()
        );

        return $companyAddress->address;
    }
}
