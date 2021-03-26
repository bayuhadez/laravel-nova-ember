<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffCategory extends Model
{
    protected $table = 'staff_categories';
    protected $fillable = ['name'];

	public function staffs()
	{
        return $this->belongsToMany(
            'App\Models\Staff',
            'staff_staffcategory',
            'staff_category_id',
            'staff_id'
        );
    }
    
    /**
     * Scope for datatable column filters
     */
    public function scopeNameLike($q, $term)
    {
        $q->where('name', 'like', '%'.$term.'%');
    }
}
