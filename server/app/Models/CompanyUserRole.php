<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyUserRole extends Pivot
{
    protected $table = 'companyuser_role';

    public $incrementing = true;

    protected $fillable = [];

    public function companyUser()
    {
        return $this->belongsTo('App\Models\CompanyUser');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }
}
