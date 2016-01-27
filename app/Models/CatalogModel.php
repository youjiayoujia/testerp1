<?php

namespace App\Models;

use App\Base\BaseModel;
use Illuminate\Support\Facades\DB;

class CatalogModel extends BaseModel
{
    protected $table = 'catalogs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    protected $searchFields = ['name'];

    protected $rules = [
        'create' => ['name' => 'required|unique:catalogs,name'],
        'update' => ['name' => 'required|unique:catalogs,name,{id}']
    ];

    public function sets()
    {
        return $this->hasMany('App\Models\Catalog\SetModel','catalog_id');
    }

    public function attributes()
    {
        return $this->hasMany('App\Models\Catalog\AttributeModel','catalog_id');
    }

    public function features()
    {
        return $this->hasMany('App\Models\Catalog\FeatureModel','catalog_id');
    }

    public function createCatalog($data,$extra=[])
    {
        DB::beginTransaction();
        $catalog = $this->create($data);
        //属性名属性值添加
        if ($extra) {
            foreach ($extra as $model => $property) {
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

    public function updateCatalog($catalogModel,$data,$extra=[])
    {
        DB::beginTransaction();
        //echo '<pre>';
        //print_r($extra);exit;
        //更新分类信息
        $catalogModel->update($data);
        //更新分类属性
        if($extra){
            try {
                    foreach ($extra as $model=>$property) {
                        foreach($property as $valueModel){
                            if(array_key_exists("id",$valueModel)){
                                //更新属性名属性值
                                $modelObj =  $catalogModel->$model()->find($valueModel['id']);
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
                                $newset = $catalogModel->$model()->create($valueModel);
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

    public function destoryCatalog($id)
    {
        $extras = ['attributes','sets','features'];
        //找到catalog model
        $catalog = $this->find($id);
        //删除对应的属性
        foreach ($extras as $models) {
            //$models .="()";
            print_r($catalog->attributes);exit;
            foreach ($catalog->$models as $model) {
                //echo '<pre>';
                //print_r($catalog->attributes()->delete());exit;
                foreach ($model->values as $value) {
                    $value->delete();
                }
                $model->delete();
            }
        }
        //删除catalog
        $catalog->delete();
    }

}
