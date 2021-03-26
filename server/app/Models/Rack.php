<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    protected $fillable = ['code', 'name', 'description', 'quantity'];
    
    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse');
    }

    public function productStocks()
    {
        return $this->hasMany('App\Models\ProductStock');
    }

    public function scopeInWarehouse($q, $warehouseId)
    {
        return $q->where('racks.warehouse_id', '=', $warehouseId);
    }

    public function scopeCodeExist($q, $code)
    {
        return $q->where('racks.code', '=', $code);
    }

    public function scopeNameExist($q, $name)
    {
        return $q->where('racks.name', '=', $name);
    }
}
