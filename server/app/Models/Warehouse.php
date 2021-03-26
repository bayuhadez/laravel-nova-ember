<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'person_in_charge'
    ];

    public function companies()
    {
        return $this->belongsToMany(
            'App\Models\Company',
            'company_warehouse',
            'warehouse_id',
            'company_id'
        );
    }
    
    public function warehouseCategories()
    {
        return $this->belongsToMany(
            'App\Models\WarehouseCategory',
            'warehouse_warehousecategory',
            'warehouse_id',
            'warehouse_category_id'
        );
    }

    public function companyWarehouses()
    {
        return $this->hasMany('App\Models\CompanyWarehouse', 'warehouse_id');
    }

    public function racks()
    {
        return $this->hasMany('App\Models\Rack');
    }

    public function scopeCodeExist($q, $code)
    {
        return $q->where('warehouses.code', '=', $code);
    }

    public function scopeNameExist($q, $name)
    {
        return $q->where('warehouses.name', '=', $name);
    }

    public function scopeInCompany($q, $companyId)
    {
        return $q->whereHas('companies', function ($q) use ($companyId) {
            $q->where('companies.id', '=', $companyId);
        });
    }
}
