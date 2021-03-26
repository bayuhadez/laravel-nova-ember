<?php

namespace App\Models;

use App\Services\MidtransService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MidtransTransaction extends Model
{
    protected $table = 'midtrans_transactions';

	protected $fillable = [
		'bank',
		'order_id',
		'order_number',
		'payment_type',
		'status_code',
		'raw_json',
	];

	public function order()
	{
		return $this->belongsTo('App\Models\Order');
	}

	public function getPaymentTypeTextAttribute()
	{
		return Str::title(str_replace('_', ' ', $this->payment_type));
	}

	/**
	 * Accessor to get midtrans instruction pdf url
	 *
	 * @return string
	 */
	public function getInstructionPdfUrlAttribute()
	{
		$midtransService = new MidtransService();
		return $midtransService->getInstructionPdfUrl($this->order);
	}

}
