<?php

namespace App\Models;

use App\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use App\Models\ChannelModel;
use Tool;

class CatalogModel extends BaseModel
{
    protected $table = 'catalogs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name','c_name','code'];

    public $searchFields = ['id'=>'ID','c_name'=>'名称'];

    public $rules = [
        'create' => ['name' => 'required|unique:catalogs,name',
                     'c_name' => 'required|unique:catalogs,name',
                     ////'sets.0.name' => 'required',
                     //'sets.0.value.name.0.name' => 'required',
                     //'variations.0.name' => 'required',
                     //'variations.0.value.name.0.name' => 'required',
                    ],
        'update' => ['name' => 'required|unique:catalogs,name,{id}','c_name' => 'required|unique:catalogs,c_name,{id}',]
    ];

    public function sets()
    {
        return $this->hasMany('App\Models\Catalog\SetModel','catalog_id');
    }

    public function variations()
    {
        return $this->hasMany('App\Models\Catalog\VariationModel','catalog_id');
    }

    public function features()
    {
        return $this->hasMany('App\Models\Catalog\FeatureModel','catalog_id');
    }

    public function getAllNameAttribute()
    {
        $name = $this->c_name."(".$this->name.")";

        return $name;
    }

    public function channelRates()
    {
        return $this->belongsToMany('App\Models\ChannelModel','catalog_channels','catalog_id','channel_id')->withPivot('rate', 'flat_rate')->withTimestamps();
    }

    public function channels()
    {
        return $this->belongsToMany('App\Models\Channel\CatalogRatesModel','catalog_rates_channels_catalogs','catalog_id','channel_id')->withPivot('rate', 'flat_rate')->withTimestamps();
    }

    public function createCatalog($data,$extra=[])
    {
        DB::beginTransaction();
        $catalog = $this->create($data);
        foreach($data['channel']['name'] as $channel_id=>$rate){
            $arr['channel_id'] = $channel_id;
            $catalog->channels()->attach($arr,['rate'=>$rate,'flat_rate'=>$data['channel']['flat'][$channel_id]]);
        }
        //属性名属性值添加
        if ($extra) {
            foreach ($extra as $model => $property) {
                if(count($property)==0){
                    if($model=='features')continue;
                    $property = [];
                    $property[$model]['name'] = "Default";
                    $property[$model]['value']['name'][0]['name'] = "Default";
                }
                try {
                    foreach ($property as $modelData) {
                        $modelObj = $catalog->$model()->create($modelData);
                        foreach ($modelData['value'] as $valueModel) {
                            foreach($valueModel as $valueModelValue){
                                if($valueModelValue['name']!=''){
                                    $modelObj->values()->create($valueModelValue);
                                }                             
                            }
                        }
                    }
                } catch (Exception $e) {
                    DB::rollBack();
                }
            }
        }
        DB::commit();
        return $catalog;        
    }

    public function updateCatalog($data,$extra=[])
    {
        DB::beginTransaction();
        //更新分类信息
        $catalog = $this->update($data);
        $arr=[];
        if(array_key_exists('channel', $data)){
            foreach($data['channel']['name'] as $channel_id=>$rate){
                $brr['rate'] = $rate;
                $brr['flat_rate'] = $data['channel']['flat'][$channel_id];
                $arr[$channel_id] = $brr;         
            }
            $this->channels()->sync($arr);
        }
        
        //更新分类属性
        if($extra){
            try {
                    foreach ($extra as $model=>$property) {
                        if($property=='')continue;
                        foreach($property as $valueModel){
                            if(array_key_exists("id",$valueModel)){
                                //更新属性名属性值
                                $modelObj =  $this->$model()->find($valueModel['id']);
                                $modelObj->update($valueModel);
                                foreach($valueModel['value'] as $valueModelValue){
                                    if(array_key_exists("id",$valueModelValue)){                                        
                                        $modelObj->values()->find($valueModelValue['id'])->update($valueModelValue);
                                    }else{
                                        if($valueModelValue['name']!=''){
                                            $modelObj->values()->create($valueModelValue);
                                        }                                        
                                    }
                                }
                            }else{//新增属性名属性值
                                $newset = $this->$model()->create($valueModel);
                                foreach($valueModel['value'] as $one){
                                    $newset->values()->create($one);
                                }
                            }
                        }
                    }

            } catch (Exception $e) {
                DB::rollBack();
            }
        }
        DB::commit();
    }

    public function destoryCatalog()
    {
        $extras = ['variations','sets','features'];
        //删除对应的属性
        foreach ($extras as $models) {
            foreach ($this->$models as $model) {
                foreach ($model->values as $value) {
                    $value->delete();
                }
                $model->delete();
            }
        }
        //删除catalog
        $this->delete();
    }

    /**
     * 获取笛卡尔积model集合
     * 2016-1-6 16:15:22 YJ
     * @param int catalog_id 品类id
     * @return array
     */
    public function getModels()
    {
        $brr = [];
        //获得product对应set的笛卡尔积
        foreach($this->sets as $set){
            $arr = [];
            foreach($set->values as $setValue){
                $arr[] = $setValue->name;
            }
            $brr[] =$arr;
        }
        $result = Tool::createDikaer($brr);
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
     * jq获得产品属性
     * 2016-1-11 14:00:41 YJ
     * @param int $catalog_id ,$product_id 品类及产品ID
     * @return array
     */
    public function getCatalogProperty($catalog_id)
    {
        $catalog = $this->find($catalog_id);
        $set = ['variations', 'features'];
        $data = [];
        $modelSet = $catalog->getModels();
        foreach ($set as $models) {
            $i = 0;
            foreach ($catalog->$models as $model) {
                $data[$models][$i]['name'] = $model->name;
                if ($models == 'features') {
                    $data[$models][$i]['type'] = $model->type;
                    $data[$models][$i]['feature_id'] = $model->id;
                }
                foreach ($model->values as $key => $value) {
                    $data[$models][$i]['value'][$value->id] = $value->name;
                }
                $i++;
            }
        }
        $data['models'] = $modelSet;
        
        return $data;
    }

    /**
     * 检查品类名是否已存在
     * 2016-4-9 17:21:17 YJ
     * @param string $catalog_name 品类名
     * @return array
     */
    public function checkName($catalog_name)
    {
        $result = $this->where("name",$catalog_name)->get();
        return count($result);
    }

    /**
     * 批量创建分类
     */
    public function createLotsCatalogs($data = NULL){
        if($data){
            DB::beginTransaction();
            foreach ($data as $item){
                try{
                    $count = $this->checkName($item['name']);
                    if($count > 0){
                        continue;
                    }
                    $catalog['c_name'] = $item['c_name'];
                    $catalog['name'] = $item['name'];
                    $catalog['code'] = $item['code'];
                    $catalog_obj = $this->create($catalog);
                    //多对多写入费率
                    foreach ($item['channel_rate'] as $key => $item_channel_value){
                        if($item_channel_value){ //若设置了对应渠道的费率
                            $channel_obj = ChannelModel::where('name','=',$key)->first();
                            $channel_ary['channel_id'] = $channel_obj->id;
                            $rate_ary = explode(',',$item_channel_value);
                            $catalog_obj->channels()->attach($channel_ary,['rate'=>$rate_ary[0],'flat_rate'=>$rate_ary[1]]);
                        }
                    }
                    //属性名属性值添加
                    if ($item['attributes']) {
                        foreach ($item['attributes'] as $model => $property) {
                            if(count($property)==0){
                                if($model=='features')continue;
                                $property = [];
                                $property[$model]['name'] = "Default";
                                $property[$model]['value']['name'][0]['name'] = "Default";
                            }

                            if(!empty($property)){
                                foreach ($property as $modelData) {
                                    $modelObj = $catalog_obj->$model()->create($modelData);
                                    foreach ($modelData['value'] as $valueModel) {
                                        foreach($valueModel as $valueModelValue){
                                            if($valueModelValue['name']!=''){
                                                $modelObj->values()->create($valueModelValue);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } catch (Exception $e) {
                    DB::rollBack();
                    return false;
                }
            }
            DB::commit();
        }else{
            return false;
        }
        return true;
    }
}
