<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Scope returns units that have not been used by a specific product as its ProductUnit
     *
     * @return Query
     */
    public function scopeNotUsedByProduct($q, $productId)
    {
        $excludedUnitIds = ProductUnit::where('product_id', $productId)->get()->pluck('unit_id')->all();
        return $q->whereNotIn('id', $excludedUnitIds);
    }
}
