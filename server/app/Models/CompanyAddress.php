<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
    protected $table = 'company_address';

    protected $fillable = [
        'is_default',
    ];
    
    public function address()
    {
        return $this->belongsTo('App\Models\Address', 'address_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
    
    public function scopeInCompany($q, $companyId)
    {
        return $q->where('company_address.company_id', '=', $companyId);
    }   
    
    public function scopeInAddress($q, $addressId)
    {
        return $q->where('company_address.address_id', '=', $addressId);
    }
}
