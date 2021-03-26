<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $table = 'product_unit';
    protected $fillable = [
        'conversion_rate',
        'is_primary'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function convertedUnit()
    {
        return $this->belongsTo('App\Models\Unit', 'converted_unit_id');
    }
}
