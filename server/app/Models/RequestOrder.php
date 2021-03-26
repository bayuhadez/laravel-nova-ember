<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestOrder extends Model
{
    use SoftDeletes;

    const STATUS_REQUEST_ORDER = 0;
    const STATUS_PRE_ORDER = 1;
    const STATUS_DRAFT = 2;

    protected $table = 'request_orders';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    protected $fillable = [
        'memo',
        'number',
        'status',
    ];

    /**
     * Get the company that owns the requestOrder.
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * Get the user creator that owns the requestOrder.
     */
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    /**
     * Get the staff that owns the requestOrder.
     */
    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }

    /**
     * Get the staffPosition that owns the requestOrder.
     */
    public function staffPosition()
    {
        return $this->belongsTo('App\Models\StaffPosition');
    }

    /**
     * Get the requestOrderProductss for the requestOrder
     */
    public function requestOrderProducts()
    {
        return $this->hasMany('App\Models\RequestOrderProduct');
    }

    /**
     * Get the preOrder that owns the requestOrder
     */
    public function preOrder()
    {
        return $this->belongsTo('App\Models\PreOrder');
    }

    /** scopes: **/
    /**
     * Scope a query to only include items that are related with specifict pre order.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param integer $preOrderId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInPreOrder($q, $preOrderId)
    {
        return $q->where('pre_order_id', $preOrderId);
    }

    /**
     * Scope for datatable column filters
     */
    public function scopeCompanyNameLike($q, $term)
    {
        $q->whereHas('company', function ($q) use ($term) {
            $q->where('name', 'like', '%'.$term.'%');
        });
    }

    public function scopeCompanyDivisionFilter($q, $term)
    {
        $q->whereHas('company', function ($q) use ($term) {
            $q->where('division', $term);
        });
    }

    public function scopeStatusFilter($q, $term)
    {
        $q->where('status', $term);
    }

    public function scopeNumberLike($q, $term)
    {
        $q->where('number', 'like', '%'.$term.'%');
    }

    public function scopeCreatedAtLike($q, $term)
    {
        $q->where('created_at', 'like', '%'.$term.'%');
    }

    public function scopeExclude($q, array $excludeIds)
    {
        return $q->whereNotIn('request_orders.id', $excludeIds);
    }

    public function scopeInCompany($q, $companyId)
    {
        return $q->where('request_orders.company_id', '=', $companyId);
    }
    
}
