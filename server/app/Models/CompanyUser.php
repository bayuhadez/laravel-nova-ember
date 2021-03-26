<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyUser extends Pivot
{
    protected $table = 'company_user';

    public $incrementing = true;

    protected $fillable = [];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function roles()
    {
        return $this->belongsToMany(
            'App\Models\Role',
            'companyuser_role',
            'company_user_id',
            'role_id'
        )
        ->using('App\Models\CompanyUserRole')
        ->withTimestamps();
    }

    public function stockDivisions()
    {
        return $this->belongsToMany(
            'App\Models\StockDivision',
            'companyuser_stockdivision',
            'company_user_id',
            'stock_division_id'
        )
        ->withTimestamps();
    }
}
