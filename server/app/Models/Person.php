<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
	protected $fillable = [
		'user_id',
		'first_name',
		'last_name',
		'telephone_number',
		'mobile_number',
		'fax_number',
		'registration_certificate_number',
	];

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

    public function staffs()
	{
		return $this->hasMany('App\Models\Staff', 'person_id');
	}

    public function addresses()
    {
        return $this->belongsToMany(
            'App\Models\Address',
            'person_address',
            'person_id',
            'address_id'
        )->withPivot('is_default');
    }

    public function personAddresses()
    {
        return $this->hasMany('App\Models\PersonAddress', 'person_id');
    }

	public function getFullnameAttribute()
	{
		return ($this->first_name . ' ' . $this->last_name);
	}

	public function regency()
	{
		return $this->belongsTo('App\Models\Regency');
	}

    public function scopePeopleInCompany($q, $companyId)
    {
        return $q->whereHas('staffs', function($q) use ($companyId) {
            $q->where('staffs.company_id', '=', $companyId);
        });
    }
}
