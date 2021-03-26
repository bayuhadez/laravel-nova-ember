<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffPosition extends Model
{
    protected $table = 'staff_positions';
    protected $fillable = ['name'];

	public function staffs()
	{
        return $this->belongsToMany(
            'App\Models\Staff',
            'staff_staffposition',
            'staff_position_id',
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
