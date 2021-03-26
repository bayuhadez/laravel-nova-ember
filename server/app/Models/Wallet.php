<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';

    protected $fillable = [
        'code',
        'name',
        'payment_type',
        'amount',
    ];

    public function paymentMethods()
    {
        return $this->belongsToMany(
            'App\Models\PaymentMethod',
            'paymentmethod_wallet',
            'wallet_id',
            'payment_method_id'
        );
    }

    public function companies()
    {
        return $this->belongsToMany(
            'App\Models\Company',
            'company_wallet',
            'wallet_id',
            'company_id'
        );
    }
}
