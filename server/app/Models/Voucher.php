<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
	protected $fillable = [
		'company_id',
		'name',
		'stock',
		'amount'
    ];

	public function company()
	{
		return $this->belongsTo('App\Models\Company');
	}

	public function orders()
	{
		return $this->hasMany('App\Models\Order');
	}
}
