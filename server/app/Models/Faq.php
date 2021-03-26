<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    // Relations:
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
}
