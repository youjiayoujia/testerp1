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
    protected $fillable = ['name', 'url', 'company', 'official_url', 'contact_name', 'email', 'province', 'city', 'address', 'type', 'telephone', 'purchase_id', 'level_id', 'created_by','purchase_time','bank_account','bank_code','pay_type','qualifications','examine_status'];

    //查询
    public $searchFields = ['name','telephone']; 

    //验证规则
    public $rules = [
            'create' => [   
                    'name' => 'required|max:128|unique:product_suppliers,name',
                    'purchase_id' => 'required|integer',
                    'telephone' => 'required|max:256|digits_between:8,11'
            ],
            'update' => [   
                    'name' => 'required|max:128|unique:product_suppliers,name, {id}',
                    'purchase_id' => 'required|integer',
                    'telephone' => 'required|max:256|digits_between:8,11'
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
	public function getSupplierAddressAttribute(){
		return $this->province.$this->city.$this->address;
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
    public function supplierCreate($data, $file= null)
    {	
			if($data['type']==0){
            	$path = 'uploadSupplier/';
				if ($file->getClientOriginalName()) {
					$originalExtension=$file->getClientOriginalExtension();
					if($originalExtension=='jpg' || $originalExtension=='png' || $originalExtension=='jpeg'){
						$data['qualifications'] = $path.time() . '.' . $file->getClientOriginalExtension();
						$file->move($path, $data['qualifications']);
					}else{
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
    public function updateSupplier($id,$data, $file= null)
    {		
		if($data['type']==0){
            $path = 'uploadSupplier/';
			if ($file->getClientOriginalName()){
				$originalExtension=$file->getClientOriginalExtension();
					if($originalExtension=='jpg' || $originalExtension=='png' || $originalExtension=='jpeg'){
					$data['qualifications'] = $path.time() . '.' . $file->getClientOriginalExtension();
					$file->move($path, $data['qualifications']);
					}else{
						return 'imageError';
						}
			}         
    	}
		return $this->find($id)->update($data);
		}
}
