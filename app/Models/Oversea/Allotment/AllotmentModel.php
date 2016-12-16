<?php

namespace App\Models\Oversea\Allotment;

use App\Base\BaseModel;

class AllotmentModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oversead_allotments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['allotment_num', 'out_warehouse_id', 'in_warehouse_id', 'logistics_id', 'allotment_by', 'status', 'check_by', 'check_status', 'created_at'];

    public function getLimits()
    {
        $arr = [];
        foreach($this->boxes as $box) {
            $arr['create']['boxInfo.'.$box->id.'.length'] = 'required';
            $arr['create']['boxInfo.'.$box->id.'.width'] = 'required';
            $arr['create']['boxInfo.'.$box->id.'.height'] = 'required';
            $arr['create']['boxInfo.'.$box->id.'.weight'] = 'required';
        }

        return $arr;
    }

    //查询
    public $searchFields=['allotment_num' => '调拨单号'];

    public function outWarehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'out_warehouse_id', 'id');
    }

    public function inWarehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'in_warehouse_id', 'id');
    }

    public function boxes()
    {
        return $this->hasMany('App\Models\Oversea\Box\BoxModel', 'parent_id', 'id');
    }

    public function allotmentBy()
    {
        return $this->belongsTo('App\Models\UserModel', 'allotment_by', 'id');
    }

    public function checkBy()
    {
        return $this->belongsTo('App\Models\UserModel', 'check_by', 'id');
    }

    public function allotmentForms()
    {
        return $this->hasMany('App\Models\Oversea\Allotment\AllotmentFormModel', 'parent_id', 'id');
    }

    public function getStatusNameAttribute()
    {
        return config('oversea.allotmentStatus')[$this->status];
    }

    public function logistics()
    {
        return $this->belongsTo('App\Models\Oversea\FirstLeg\FirstLegModel', 'logistics_id', 'id');
    }
}