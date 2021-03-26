<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeminarProductSponsor extends Model
{
    protected $table = 'seminar_product_sponsors';

    protected $fillable = [
        'sponsor_image_path',
        'sponsor_name'
    ];

    public function seminarProductMeta()
    {
        return $this->belongsTo('App\Models\SeminarProductMeta', 'seminar_product_meta_id');
    }

    /*
    public function product()
    {
        return $this->hasOneThrough('App\Models\Product', 'App\Models\SeminarProductMeta');
    }
    */
}
