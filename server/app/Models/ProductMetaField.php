<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMetaField extends Model
{
    protected $table = 'product_meta_fields';

    protected $fillable = [
        'label',
    ];

    public function productMetaFieldGroup()
    {
        return $this->belongsTo('App\Models\ProductMetaFieldGroup');
    }

    public function productMetaValues()
    {
        return $this->hasMany('App\Models\ProductMetaValue');
    }

    /**
     * Scope for datatable column filters
     */
    public function scopeLabelLike($q, $term)
    {
        $q->where('label', 'like', '%'.$term.'%');
    }
}
