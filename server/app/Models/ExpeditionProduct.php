<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpeditionProduct extends Model
{
    protected $table = 'expedition_product';
    protected $fillable = ['amount'];

    public function expedition()
    {
        return $this->belongsTo('App\Models\Expedition');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
