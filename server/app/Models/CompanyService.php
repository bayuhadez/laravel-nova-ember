<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyService extends Model
{
    protected $table = 'company_service';

    protected $fillable = [
        'point',
        'price'
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function service()
    {
        return $this->belongsTo('App\Models\Service');
    }
}
