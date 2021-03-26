<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierCategory extends Model
{
    protected $table = 'supplier_categories';

    protected $fillable = ['name'];

    public function suppliers()
    {
        return $this->hasMany('App\Models\Supplier');
    }
}
