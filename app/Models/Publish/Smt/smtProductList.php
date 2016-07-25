<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;

class smtProductList extends BaseModel
{
    protected $table = "smt_product_list";
    
    protected $fillable = [       
        'product_url',
        'productId',
        'token_id',
        'user_id',
        'ownerMemberId',
        'ownerMemberSeq',
        'subject',
        'productPrice',
        'productMinPrice',
        'productMaxPrice',
        'productStatusType',
        'gmtCreate',
        'gmtModified',
        'wsOfflineDate',
        'wsDisplay',
        'groupId',
        'categoryId',
        'packageLength',
        'packageWidth',
        'packageHeight',
        'grossWeight',
        'deliveryTime',
        'wsValidNum',
        'multiattribute',
        'synchronizationTime',   
        'isRemove',
        'old_token_id',
        'old_productId'
    ];
  
    protected $searchFields = [];
    
    protected $rules = [];
    
    public function details()
    {
        return $this->hasOne('App\Models\Publish\Smt\smtProductDetail', 'productId', 'productId');
    }
    
    public function accounts(){
        return $this->belongsTo('App\Models\Channel\AccountModel', 'token_id');
    }
    
    public function  productSku(){
        return $this->hasMany('App\Models\Publish\Smt\smtProductSku','productId','productId');
    }
    
}
