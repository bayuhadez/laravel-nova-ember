<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staffs';

    protected $fillable = [
        'code',
    ];

    public function person()
    {
        return $this->belongsTo('App\Models\Person');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function staffCategories()
    {
        return $this->belongsToMany(
            'App\Models\StaffCategory',
            'staff_staffcategory',
            'staff_id',
            'staff_category_id'
        );
    }
    
    public function staffPositions()
    {
        return $this->belongsToMany(
            'App\Models\StaffPosition',
            'staff_staffposition',
            'staff_id',
            'staff_position_id'
        );
    }

    public function companyCustomers()
    {
        return $this->belongsToMany(
            'App\Models\CompanyCustomer',
            'companycustomer_staff',
            'staff_id',
            'company_customer_id'
        );
    }

    /**
     * Scope for datatable column filters
     */
	public function scopeCodeLike($q, $term)
    {
        $q->where('code', 'like', '%'.$term.'%');
	}
	
	public function scopePersonFirstNameLike($q, $term)
    {
        $q->whereHas('person', function ($q) use ($term) {
            $q->where('first_name', $term);
        });
    }

    public function scopePersonLastNameLike($q, $term)
    {
        $q->whereHas('person', function ($q) use ($term) {
            $q->where('last_name', $term);
        });
    }

    public function scopePersonAddressLike($q, $term)
    {
        $q->whereHas('person', function ($q) use ($term) {
            $q->whereHas('addresses', function ($q) use ($term) {
                $q->where('address', $term);
            });
        });
    }

    public function scopePersonPhoneLike($q, $term)
    {
        $q->whereHas('person', function ($q) use ($term) {
            $q->where('telephone_number', $term);
        });
    }

    public function scopePersonRegencyNameLike($q, $term)
    {
        $q->whereHas('person', function ($q) use ($term) {
            $q->whereHas('regency', function ($q) use ($term) {
                $q->where('name', $term);
            });
        });
    }

    public function scopeCompanyNameLike($q, $term)
    {
        $q->whereHas('company', function ($q) use ($term) {
            $q->where('name', $term);
        });
    }

    public function scopeStaffCategoryNameLike($q, $term)
    {
        $q->whereHas('staffCategories', function ($q) use ($term) {
            $q->where('name', $term);
        });
    }

    public function scopeStaffPositionNameLike($q, $term)
    {
        $q->whereHas('staffPositions', function ($q) use ($term) {
            $q->where('name', $term);
        });
    }

    public function scopeInCompany($q, $companyId)
    {
        return $q->where('company_id', '=', $companyId);
    }

    public function getFullnameAttribute()
    {
        return $this->person->fullname;
    }
}
