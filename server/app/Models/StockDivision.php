<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockDivision extends Model
{
    const DIVISION_WHOLESALE = 1;
    const DIVISION_RETAIL = 2;

    protected $table = 'stock_divisions';

    protected $fillable = [
        'company_id',
        'division',
        'description',
        'name',
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function productStocks()
    {
        return $this->hasMany('App\Models\ProductStock', 'stock_division_id');
    }

    public function getDivisionTextAttribute()
    {
        switch ($this->division) {
            case self::DIVISION_WHOLESALE: return 'Wholesale'; break;
            case self::DIVISION_RETAIL: return 'Retail'; break;
            default: return ''; break;
        }
    }

    public function scopeInCompany($q, $companyId)
    {
        $q->where('company_id', $companyId);
    }
}
