<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\CatalogModel;

class ProductModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','model','name','c_name','alias_name','alias_cname','catalog_id','supplier_id','supplier_info','purchase_url','product_sale_url','purchase_price',
                            'purchase_carriage','product_size','package_size','weight','upload_user','assigner','default_image','carriage_limit',
                            'carriage_limit_1','package_limit','package_limit_1','status','remark','spu_id','second_supplier_id','supplier_sku'];



    public function image()
    {
        return $this->belongsTo('App\Models\Product\ImageModel','default_image');
    }

    public function catalog()
    {
        return $this->belongsTo('App\Models\CatalogModel','catalog_id');
    }

    public function spu()
    {
        return $this->belongsTo('App\Models\SpuModel','spu_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel','supplier_id');
    }

    public function productAttributeValue()
    {      
        return $this->hasMany('App\Models\Product\ProductAttributeValueModel','product_id');
    }

    public function getCatalogs($id='',$where='')
    {      
        $catalog = new CatalogModel();
        return $catalog->getCatalog($id,$where);
    }

    /**
     * 获取笛卡尔积model集合
     * 2016-1-6 16:15:22 YJ
     * @param int catalog_id 品类id
     * @return array
     */
    public function getModels($catalog_id=0)
    {
        if($catalog_id==0){
            $catalog = $this->getCatalogs('','1');          
        }else{
            $catalog = $this->getCatalogs($catalog_id);
        }
        $brr = [];
        //获得product对应set的笛卡尔积
        foreach($catalog->sets as $set){
            $arr = [];
            foreach($set->values as $setValue){
                $arr[] = $setValue->name;
            }
            $brr[] =$arr;
        }
        $result = $this->createDikaer($brr);
        $modelSet = [];
        //拼接model
        foreach($result as $_result){
            $sku = '';
            foreach($_result as $__result){
                $sku .= '-'.$__result;
            }
            $sku = substr($sku,1);
            $modelSet[] = $sku;
        }
        return $modelSet;
    }

    /**
     * 随机创建sku
     * 2015-12-18 10:43:21 YJ
     * @return str
     */
    public function createSku()
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;
        for($i=0;$i<5;$i++){
            $str.=$strPol[rand(0,$max)];
        }
        return $str;
 
    }
    /**
     * 创建sku对应属性的笛卡尔积
     * 2015-12-18 10:43:48 YJ
     * @param array $data
     * @return array
     */
    public function createDikaer($data)
    {
        $cnt = count($data);  
        $result = array();  
        foreach($data[0] as $item) {  
            $result[] = array($item);  
        }  
        for($i = 1; $i < $cnt; $i++) {  
            $result = $this->combineArray($result,$data[$i]);  
        }  
        return $result;
 
    }

    /**
     * 2个数组对笛卡尔积的处理
     * 2015-12-18 10:43:48 YJ
     * @param array $arr1,$arr2
     * @return array
     */
    function combineArray($arr1,$arr2) {  
        $result = array();  
        foreach ($arr1 as $item1) {  
            foreach ($arr2 as $item2) {  
                $temp = $item1;  
                $temp[] = $item2;  
                $result[] = $temp;  
            }  
        }  
        return $result;  
    }

    /**
     * jq获得产品属性
     * 2016-1-11 14:00:41 YJ
     * @param int $catalog_id,$product_id 品类及产品ID
     * @return array
     */
    function getCatalogProperty($catalog_id,$product_id=0) {  
        $catalog = $this->getCatalogs($catalog_id);
        $set = ['Attributes', 'features'];
        $data = [];           
        $modelSet = $this->getModels($catalog_id);                              
        foreach($set as $models){
            $i = 0;
            foreach($catalog->$models as $model){
                $data[$models][$i]['name'] = $model->name;
                if($models=='features'){
                    $data[$models][$i]['type'] = $model->type;
                    $data[$models][$i]['feature_id'] = $model->id;
                }
                foreach($model->values as $key=>$value){
                    $data[$models][$i]['value'][] = $value->name; 
                }               
                $i++;
            }  
        }
        $data['models'] = $modelSet;
echo '<pre>';
        print_r($data);exit;
        return $data;
    }

}
