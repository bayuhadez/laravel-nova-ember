<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonAddress extends Model
{
    protected $table = 'person_address';

    protected $fillable = [
        'is_default',
    ];

    public function address()
    {
        return $this->belongsTo('App\Models\Address', 'address_id');
    }
    
    public function person()
    {
        return $this->belongsTo('App\Models\Person', 'person_id');
    }

    public function scopeInPerson($q, $personId)
    {
        return $q->where('person_address.person_id', '=', $personId);
    }

    public function scopeInAddress($q, $addressId)
    {
        return $q->where('person_address.address_id', '=', $addressId);
    }
}
