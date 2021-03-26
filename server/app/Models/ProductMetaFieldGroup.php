<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMetaFieldGroup extends Model
{
    protected $table = 'product_meta_field_groups';

    protected $fillable = [
        'name',
    ];

    public function productMetaFields()
    {
        return $this->hasMany('App\Models\ProductMetaField');
    }
}
