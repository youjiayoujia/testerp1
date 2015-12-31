<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\Catalog\CatalogModel;
use Illuminate\Support\Facades\DB;
/**
 * 品类库
 *
 * @author youjia
 */
class CatalogRepository extends BaseRepository
{
    protected $searchFields = ['name'];
    public $rules = [
        'create' => ['name' => 'required|unique:catalogs,name'],
        'update' => ['name' => 'required|unique:catalogs,name,{id}']
    ];
    public function __construct(CatalogModel $catalog)
    {
        $this->model = $catalog;
    }
    /**
     * 新增品类
     * 2015-12-22 10:49:08 YJ
     * @param array $data 包含品类名的数组
     * @param array extra 属性值、名对应的数组
     * @return App\Models\Catalog Object
     */
    public function create($data,$extra=[])
    {   
        DB::beginTransaction();
        $catalog = $this->model->create($data);
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
    /**
     * 更新品类
     * 2015-12-22 10:49:08 YJ
     * @param int   $id   更新品类对应的id
     * @param array $data 包含品类名的数组
     * @param array extra 属性值、名对应的数组
     * @return App\Models\Catalog Object
     */
    public function update($id, $data,$extra=[])
    {
        DB::beginTransaction();
        //获得catalog对象
        $catalog = $this->get($id);
        //更新品类名
        $catalog->update($data);
        if($extra){
            try {
                    foreach ($extra as $model=>$property) {
                        foreach($property as $valueModel){
                            if(array_key_exists("id",$valueModel)){
                                //更新属性名属性值
                                $modelObj =  $catalog->$model()->find($valueModel['id']);
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
                                $newset = $catalog->$model()->create($valueModel);
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
        return $catalog;
    }

    /**
     * 删除品类及对应属性
     * @author YJ 2015-12-28 08:57:26
     * @param int   $id   删除品类对应的id
     * @return 1
     */
    public function destroy($id)
    {
        $extras = ['sets', 'features', 'attributes'];
        //找到catalog model
        $catalog = $this->get($id);
        //删除对应的属性
        foreach ($extras as $models) {
            foreach ($catalog->$models as $model) {
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