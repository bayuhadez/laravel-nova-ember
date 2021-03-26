<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $table = 'service_categories';

    protected $fillable = [
        'name'
    ];

    public function service()
    {
		return $this->hasMany('App\Models\Service');
    }

    /**
     * Scope for datatable column filters
     */
    public function scopeNameLike($q, $term)
    {
      $q->where('name', 'like', '%'.$term.'%');
    }

}
