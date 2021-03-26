<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $fillable = ['province_id', 'name'];

    public function supplier()
    {
        return $this->hasOne('App\Models\Supplier');
    }

    public function province()
    {
        return $this->belongsTo('App\Models\Province');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function scopeInProvince($q, $provinceId)
    {
        return $q->where('regencies.province_id', '=', $provinceId);
    }
}
