<?php
namespace App\Models;

use App\Base\BaseModel;

class PackageModel extends BaseModel
{
    protected $table = 'packages';

    public $searchFields = ['email'];

    public $rules = [
        'create' => ['order_id' => 'required'],
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

    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'channel_account_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\OrderModel', 'order_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id');
    }

    public function picklist()
    {
        return $this->belongsTo('App\Models\PickListModel', 'picklist_id');
    }

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Package\ItemModel', 'package_id');
    }

    public function listItemPackage()
    {
        return $this->hasMany('App\Models\Pick\ListItemPackageModel', 'package_id', 'id');
    }

    public function manualLogistic()
    {
        return $this->hasMany('App\Models\Package\LogisticModel', 'package_id', 'id');
    }

    public function getStatusNameAttribute()
    {
        $arr = config('pick.package');
        return $arr[$this->status];
    }
}
