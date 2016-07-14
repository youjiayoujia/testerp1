<?php

namespace App\Models\product;

use App\Base\BaseModel;
use Tool;

class SupplierModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'url',
        'company',
        'official_url',
        'contact_name',
        'email',
        'province',
        'city',
        'address',
        'type',
        'telephone',
        'purchase_id',
        'level_id',
        'created_by',
        'purchase_time',
        'bank_account',
        'bank_code',
        'pay_type',
        'qualifications',
        'examine_status',
        'qq',
        'wangwang'
    ];

    //查询
    public $searchFields = ['company'=>'公司名称', 'telephone'=>'手机','contact_name'=>'联系人','qq'=>'QQ','wangwang'=>'旺旺'];

    //验证规则
    public $rules = [
        'create' => [
/*            'name' => 'required|max:128|unique:product_suppliers,name',*/
            'purchase_id' => 'required|integer',
            'telephone' => 'required|max:256|digits_between:8,11',
            'purchase_time' => 'required|integer',
            'bank_account' => 'required|string',
        ],
        'update' => [
/*            'name' => 'required|max:128',*/
            'purchase_id' => 'required|integer',
            'telephone' => 'required|max:256|digits_between:8,11',
            'purchase_time' => 'required|integer',
            'bank_account' => 'required|string',

        ]
    ];

    /**
     * return the relation between the two module
     *
     * @return relation
     */
    public function purchaseName()
    {
        return $this->belongsTo('App\Models\UserModel', 'purchase_id', 'id');
    }

    //获取供应商地址
    public function getSupplierAddressAttribute()
    {
        return $this->province . $this->city . $this->address;
    }

    /**
     * return the relation between the two module
     *
     * @return relation
     */
    public function createdByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'created_by', 'id');
    }

    /**
     * return the relation between the two module
     *
     * @return relation
     */
    public function levelByName()
    {
        return $this->belongsTo('App\Models\Product\SupplierLevelModel', 'level_id', 'id');
    }

    /**
     * 创建新供应商
     *
     *
     */
    public function supplierCreate($data, $file = null)
    {
        if ($data['type'] == 0 && $file != null) {

            $path = config('product.product_supplier.file_path');
            if ($file->getClientOriginalName()) {
                $originalExtension = $file->getClientOriginalExtension();
                if ($originalExtension != 'php'){
                    $data['qualifications'] = Tool::randString(16,false) . '.' . $file->getClientOriginalExtension();
                    $file->move($path, $data['qualifications']);
                } else {
                    return 'imageError';
                }
            }
        }
        return $this->create($data);
    }

    /**
     * 创建新供应商
     *
     *
     */
    public function updateSupplier($id, $data, $file = null)
    {
        if ($data['type'] == 0 && $file != null) { //线下类型
            $path = config('product.product_supplier.file_path');
                if ($file->getClientOriginalName()) {
                    $itemInfo = $this->where('id',$id)->first();
                    $originPath = $itemInfo['qualifications']; //原来文件路径
                    $originalExtension = $file->getClientOriginalExtension();
                    if ($originalExtension != 'php') {
                        $data['qualifications'] = Tool::randString(16,false) . '.' . $file->getClientOriginalExtension();
                        if($file->move($path, $data['qualifications']) && $originPath != ''){
                            if(file_exists('./'.$path.$originPath)){
                                unlink('./'.$path.$originPath); //删除原来的文件
                            }
                        }
                    } else {
                        return 'imageError';
                    }
                }
        }else{
            $data['qualifications'] = '';
        }
        return $this->find($id)->update($data);
    }
}
