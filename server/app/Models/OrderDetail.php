<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';

	protected $fillable = [
		'brand',
		'category',
		'merchant_name',
		'name',
		'order_id',
		'price',
		'product_id',
		'quantity'
	];

	public function order()
	{
		return $this->belongsTo('App\Models\Order');
	}

	public function product()
	{
		return $this->belongsTo('App\Models\Product');
	}
}
