<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\ProductModel;

class SpuModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'spus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['id', 'spu','product_require_id','status','edit_user','image_user'];
    protected $guarded = [];

    public $searchFields = ['id' =>'ID','spu'=>'spu'];

    public function values()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function products()
    {
        return $this->hasMany('App\Models\ProductModel', 'spu_id', 'id');
    }

    public function editUser()
    {
        return $this->belongsTo('App\Models\UserModel', 'edit_user');
    }

    public function imageEdit()
    {
        return $this->belongsTo('App\Models\UserModel', 'image_edit');
    }

    public function Purchase()
    {
        return $this->belongsTo('App\Models\UserModel', 'purchase');
    }

    public function Developer()
    {
        return $this->belongsTo('App\Models\UserModel', 'developer');
    }

    public function spuMultiOption()
    {
        return $this->hasMany('App\Models\Spu\SpuMultiOptionModel', 'spu_id');
    }

    /**
     * 更新多渠道多语言信息
     * 2016年6月3日10:43:18 YJ
     * @param array data 修改的信息
     */
    public function updateMulti($data)
    {   
        foreach ($data['info'] as $channel_id => $language) {
            $arr = [];
            $pre = $language['language'];
            foreach ($language as $prefix => $value) {
                $arr[$pre."_".$prefix] = $value;
            }
            
            $model = $this->spuMultiOption->where("channel_id", (int)$channel_id)->first();
            if($model){
                $model->update($arr);
            }
           
        }
        //
    }
}