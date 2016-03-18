<?php
namespace App\Models;

use App\Base\BaseModel;

class PackageModel extends BaseModel
{
    protected $table = 'packages';

    public $searchFields = ['email'];

    public $rules = [
        'create' => [],
        'update' => [],
    ];

    protected $fillable = [
        'order_id',
        'logsitic_id',
        'picklist_id',
        'assigner_id',
        'status',
        'cost',
        'weight',
        'length',
        'width',
        'height',
        'tracking_no',
        'tracking_link',
        'email',
        'shipping_firstname',
        'shipping_lastname',
        'shipping_address',
        'shipping_address1',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_zipcode',
        'shipping_phone',
        'is_auto',
        'is_auto_logistic',
        'remark',
        'logistic_assigned_at',
        'printed_at',
        'shipped_at',
        'delivered_at',
    ];

    public function assigner()
    {
        return $this->belongsTo('App\Models\UserModel', 'assigner_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\OrderModel', 'order_id');
    }

    public function logistic()
    {
        return $this->belongsTo('App\Models\LogisticModel', 'logistic_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Package\ItemModel', 'package_id');
    }

    public function getStatusNameAttribute()
    {
        $arr = config('pick.package');
        return $arr[$this->status];
    }
}
