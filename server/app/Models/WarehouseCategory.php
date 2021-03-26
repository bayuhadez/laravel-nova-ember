<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseCategory extends Model
{
    protected $table = 'warehouse_categories';

    protected $fillable = [
        'name',
    ];

    public function warehouses()
    {
        return $this->belongsToMany(
            'App\Models\Warehouse',
            'warehouse_warehousecategory',
            'warehouse_category_id',
            'warehouse_id'
        );
    }
}
