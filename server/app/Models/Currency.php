<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'code',
        'name',
        'is_active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** ----- Scopes : **/

    public function scopeCode($q, $term)
    {
        return $q->where("{$this->getTable()}.code", '=', strtoupper($term));
    }
}
