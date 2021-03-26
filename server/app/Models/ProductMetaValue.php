<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMetaValue extends Model
{
    protected $table = 'product_meta_values';

    protected $fillable = [
        'value',
    ];

    public function productMetaField()
    {
        return $this->belongsTo('App\Models\ProductMetaField');
    }
}
