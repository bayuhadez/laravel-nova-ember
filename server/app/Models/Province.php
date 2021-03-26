<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['name'];

    public function regencies()
    {
        return $this->hasMany('App\Models\Regency');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }
}
