<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;
use App\Models\ChannelModel;

class smtProductSku extends BaseModel
{
    //
    protected  $table = "smt_product_skus";
    protected  $fillable = [
                'skuMark',
                'skuCode',
                'smtSkuCode',
                'productId',
                'sku_active_id',
                'skuPrice',
                'skuStock',
                'propertyValueId',
                'skuPropertyId',
                'propertyValueDefinitionName',
                'synchronizationTime',
                'profitRate',
                'isRemove',
                'is_new',
                'is_erp',
                'ipmSkuStock',
                'aeopSKUProperty',
                'overSeaValId',
                'updated',
                'lowerPrice',
                'discountRate'
                ];
    public $searchFields = ['productId'=>'Product ID','skuCode'=>'SKU Code'];
    
    public function getMixedSearchAttribute()
    {
        return [
            
            'filterFields' => [],
            /*'filterSelects' => [ 
                'token_id' => 
                'user_id' => $this->getArray('App\Models\UserModel','name'),
             ],*/
            
            'relatedSearchFields' => [],
            'selectRelatedSearchs' => [              
                'product' => [
                    'token_id' => $this->getAccountNumber('App\Models\Channel\AccountModel','account'),
                    'multiattribute' => config('smt_product.multiattribute'),
                    'productStatusType' => config('smt_product.productStatusType'),     
                    'user_id' => $this->getArray('App\Models\UserModel','name'),
                    ],
                'products' =>[
                    'status' => config('smt_product.status'),
                ]
            ],
            'filterSelects' => [
                'is_erp' => config('smt_product.is_erp'),
                'ipmSkuStock' => config('smt_product.skuStockStatus'),
            ]
            
        ];
    }
    
    
    public function products(){
        return $this->belongsTo('App\Models\ProductModel', 'skuCode','model');
    }
    
    public function product(){
        return $this->belongsTo('App\Models\Publish\Smt\smtProductList', 'productId','productId');
    }
    
    public function getAccountNumber($model, $name)
    {
        $channel =  ChannelModel::where('driver','aliexpress')->first();
        $arr = [];
        $inner_models = $model::where('channel_id',$channel->id)->get();
        foreach ($inner_models as $single) {
            $arr[$single->id] = $single->$name;
        }
        return $arr;
    }
    
    public function getArray($model, $name)
    {
        $arr = [];
        $inner_models = $model::all();
        foreach ($inner_models as $key => $single) {
            $arr[$single->id] = $single->$name;
        }
        return $arr;
    }
    
    
   
}
