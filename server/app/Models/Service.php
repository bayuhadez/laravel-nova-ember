<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'service_category_id',
        'price',
        'point',
    ];

    public function scopeCodeLike($q, $term)
    {
        $q->where('code', 'like', '%'.$term.'%');
    }

    public function scopeNameLike($q, $term)
    {
        $q->where('name', 'like', '%'.$term.'%');
    }

    public function scopeDescriptionLike($q, $term)
    {
        $q->where('description', 'like', '%'.$term.'%');
    }

    public function scopeServiceCategoryNameLike($q, $term)
    {
        $q->whereHas('serviceCategory', function ($q) use ($term) {
            $q->where('service_categories.name', 'like', '%'.$term.'%');
        });
    }

    public function serviceCategory()
    {
        return $this->belongsTo('App\Models\ServiceCategory');
    }

    public function companyServices()
    {
        return $this->hasMany('App\Models\CompanyService');
    }
}
