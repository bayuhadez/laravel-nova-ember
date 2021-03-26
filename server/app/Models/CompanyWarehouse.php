<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWarehouse extends Model
{
    protected $table = 'company_warehouse';
    protected $fillable = [];

    public function company()
    {
        return $this->belongsTo(
            'App\Models\Company',
            'company_id'
        );
    }
    
    public function warehouse()
    {
        return $this->belongsTo(
            'App\Models\Warehouse',
            'warehouse_id'
        );
    }

    public function scopeInCompany($q, $companyId)
    {
        return $q->where('company_warehouse.company_id', '=', $companyId);
    }

    public function scopeInWarehouse($q, $warehouseId)
    {
        return $q->where('company_warehouse.warehouse_id', '=', $warehouseId);
    }
}
