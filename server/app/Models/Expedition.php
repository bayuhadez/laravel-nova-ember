<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expedition extends Model
{
    protected $fillable = [
        'name',
        'code',
        'address',
        'regency_id',
        'currency_id',
        'pic',
        'expedition_category_id',
        'fax_number',
        'mobile_number',
        'telephone_number',
    ];

    public function expeditionProducts()
    {
        return $this->hasMany('App\Models\ExpeditionProduct');
    }

    public function expeditionCategory()
    {
        return $this->belongsTo('App\Models\ExpeditionCategory');
    }

    public function province()
    {
        return $this->belongsTo('App\Models\Province');
    }

    public function regency()
    {
        return $this->belongsTo('App\Models\Regency');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}
