<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class SalesOrder extends Model
{
    use SoftDeletes;

    protected $table = 'sales_orders';

    protected $fillable = [
        'customer_id',
        'company_id',
        'created_by',
        'number',
        'date',
        'description',
        'discount',
        'status',
        'grand_total',
        'requires_delivery',
        'ordered_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at',
        'ordered_at',
    ];

    const STATUS_PENDING = 0;
    const STATUS_DRAFT = 1;
    const STATUS_READY = 2;
    const STATUS_IN_PROCESS = 3;
    const STATUS_APPROVED = 4;
    const STATUS_ONHOLD = 5;

    public function productSalesOrders()
    {
        return $this->hasMany('App\Models\ProductSalesOrder', 'sales_order_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function warehouseStaff()
    {
        return $this->belongsTo('App\Models\Staff', 'warehouse_staff_id');
    }

    public function sales()
    {
        return $this->belongsTo('App\Models\Staff', 'sales_staff_id');
    }

    public function deliveryRecipientCustomer()
    {
        return $this->belongsTo('App\Models\Customer', 'delivery_recipient_customer_id');
    }

    public function deliveryAddress()
    {
        return $this->belongsTo('App\Models\Address', 'delivery_address_id');
    }

    public function scopeStatusReady($q)
    {
        return $q->where('sales_orders.status', '=', Self::STATUS_READY);
    }

    public function getSalesNameAttribute()
    {
        return $this->sales->fullname ?? null;
    }
}
