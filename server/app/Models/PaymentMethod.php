<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';

    protected $fillable = [
        'code',
        'name',
    ];

    public function wallets()
    {
        return $this->belongsToMany(
            'App\Models\Wallet',
            'paymentmethod_wallet',
            'payment_method_id',
            'wallet_id'
        );
    }
}
