<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
	use SoftDeletes;

	const STATUS_PENDING = 0;
	const STATUS_FAILED = 1;
	const STATUS_PROCESSING = 2;
	const STATUS_COMPLETED = 3;
	const STATUS_ONHOLD = 4;
	const STATUS_CANCELLED = 5;
	const STATUS_REFUNDED = 6;

	const PAYMENT_STATUS_PENDING = 0;
	const PAYMENT_STATUS_SETTLEMENT = 1;
	const PAYMENT_STATUS_DENIED = 2;
	const PAYMENT_STATUS_CHALLENGED = 3;
	const PAYMENT_STATUS_SUCCESS = 4;
	const PAYMENT_STATUS_EXPIRED = 5;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'company_id',
		'voucher_id',
		'gross_amount',
		'snap_transaction_token',
		'status',
	];

	public function company()
	{
		return $this->belongsTo('App\Models\Company');
	}

	public function products()
	{
		return $this->belongsToMany('App\Models\Product', 'order_details', 'order_id', 'product_id')
			->withPivot(
				'price',
				'quantity'
			)
			->withTimestamps();
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function voucher()
	{
		return $this->belongsTo('App\Models\Voucher');
	}

	public function orderDetails()
	{
		return $this->hasMany('App\Models\OrderDetail', 'order_id', 'id');
	}

	public function midtransTransaction()
	{
		return $this->hasOne('App\Models\MidtransTransaction', 'order_id', 'id');
	}

	public function transaction()
	{
		return $this->midtransTransaction();
	}

	// Accessor
	public function getPaymentIdAttribute()
	{
		return config('app.payment_id_prefix').$this->id;
	}

}
