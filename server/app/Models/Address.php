<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'address',
        'pobox'
    ];

    public function companies()
    {
        return $this->belongsToMany(
            'App\Models\Company',
            'company_address',
            'address_id',
            'company_id'
        );
    }
    
    public function people()
    {
        return $this->belongsToMany(
            'App\Models\Person',
            'person_address',
            'address_id',
            'person_id'
        );
    }
    
    public function companyAddresses()
    {
        return $this->hasMany('App\Models\CompanyAddress', 'address_id');
    }

    public function personAddresses()
    {
        return $this->hasMany('App\Models\PersonAddress', 'address_id');
    }
    
    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }
    
    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'province_id');
    }
    
    public function regency()
    {
        return $this->belongsTo('App\Models\Regency', 'regency_id');
    }

}
