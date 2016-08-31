<?php
/**
 * 产品品类控制器
 * 产品品类CURD
 * @author: youjia
 * Date: 2015-12-28 17:57:09
 */

namespace App\Http\Controllers;

use App\Models\CatalogModel;
use App\Models\ChannelModel;
use App\Models\Catalog\CatalogChannelsModel;
use App\Models\Channel\CatalogRatesModel;
use App\Models\Catalog\RatesChannelsModel;
use Excel;

class CatalogController extends Controller
{
    public function __construct(CatalogModel $catalog)
    {
        $this->model = $catalog;
        $this->mainIndex = route('catalog.index');
        $this->mainTitle = '品类Category';
        $this->viewPath = 'catalog.';
    }

    /**
     * 新建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $CatalogRatesModel = CatalogRatesModel::all();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'CatalogRatesModel' => $CatalogRatesModel,
        ];
        return view($this->viewPath . 'create', $response);
    }

    /**
     * 保存品类
     * 2015-12-18 14:38:20 YJ
     * @return Illuminate\Http\RedirectResponse Object
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        //封装数据
        $data = request()->all();
        $extra['sets'] = request()->input('sets');
        $extra['variations'] = request()->input('variations');
        $extra['features'] = request()->input('features');
        //创建品类
        $this->model->createCatalog($data, $extra);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '添加成功.'));
    }


    /**
     * 更新品类
     *
     * 2015-12-18 14:46:59 YJ
     * @param  int $id
     * @return Illuminate\Http\RedirectResponse Object
     */
    public function update($id)
    {
        $catalogModel = $this->model->find($id);
        if (!$catalogModel) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        //封装数据
        $data = request()->all();
        $extra['sets'] = request()->input('sets');
        $extra['variations'] = request()->input('variations');
        $extra['features'] = request()->input('features');
        //更新品类信息
        $catalogModel->updateCatalog($data, $extra);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '更新成功.'));
    }

    /**
     * 软删除品类
     * 2015-12-18 14:47:08 YJ
     * @param  int $id
     * @return Illuminate\Http\RedirectResponse Object
     */
    public function destroy($id)
    {
        $catalogModel = $this->model->find($id);
        $catalogModel->destoryCatalog();
        return redirect(route('catalog.index'))->with('alert', $this->alert('success', '删除成功.'));
    }

    /**
     * 检查分类名是否存在
     * 2015-12-18 14:47:08 YJ
     * @param  int $id
     * @return Illuminate\Http\RedirectResponse Object
     */
    public function checkName()
    {
        $catalog_name = request()->input('catalog_name');
        return $this->model->checkName($catalog_name);
    }

    public function index(){
        request()->flash();
        $channels = CatalogRatesModel::all();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
            'channels' => $channels,
        ];
        return view($this->viewPath . 'index',$response);

    }

    /**
     * 导出分类平台税率
     * 
     */
    public function exportCatalogRates(){

        $filters = request()->input('filter');
        $filtersArray = explode('|',$filters);
        $catalogIds = explode(',',$filtersArray[0]);
        $channelIds = explode(',',$filtersArray[1]);
        $cvsArray = [];
        $i = 1;
        $cvsArray[0] = '';
        $th = false;
        foreach ($this->model->whereIn('id',$catalogIds)->get() as $itemCatalog){
            $channelsData = $this->model->find($itemCatalog->id)->channels;
            //$cvsArray [$i][$itemCatalog->name] = $itemCatalog->name;
            $cvsArray [$i][] = $i;
            $cvsArray [$i][] = $itemCatalog->c_name;
            foreach ($channelsData as $itemChannel){
                if(in_array($itemChannel['id'],$channelIds)){
                    //<th>
                    if($i ==1){
                        $th[] = $itemChannel->name;
                    }
                    $cvsArray [$i][] = $itemChannel->pivot->rate;
                }
            }
            $i++;
        }

        if($th == false){
            return redirect(route('catalog.index'))->with('alert', $this->alert('danger', '包含没有添加税率的分类记录，请先编辑，再导出!'));
        }

        $cvsArray[0] = array_merge(['序号','分类名'],$th);

        $name = 'CatalogRates';
        Excel::create($name, function ($excel) use ($cvsArray) {
            $nameSheet = '导出分类税率';
            $excel->sheet($nameSheet, function ($sheet) use ($cvsArray) {
                $sheet->fromArray($cvsArray);
            });
        })->download('csv');
    }

    /**
     * 编辑税率
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editCatalogRates(){

        $filters = request()->input('filter');
        $filtersArray = explode('|',$filters);
        $catalogIds = explode(',',$filtersArray[0]);
        $channelIds = explode(',',$filtersArray[1]);
        $catalogs = $this->model->whereIn('id',$catalogIds)->get();
        $channels = CatalogRatesModel::whereIn('id',$channelIds)->get();
        $response =[
            'metas' => $this->metas(__FUNCTION__),
            'catalogs' => $catalogs,
            'channels' => $channels,
            'filters' => $filters
        ];
        return view($this->viewPath . 'edit_rate',$response);
    }

    /**
     * 更新税率
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCatalogRates(){

        $requestArray = request()->input();
        $filters = $requestArray['filter'];
        $filtersArray = explode('|',$filters);
        $catalogIds = explode(',',$filtersArray[0]);
        $channelIds = explode(',',$filtersArray[1]);
        foreach ($catalogIds as $catalogId){
            foreach ($channelIds as $channelId){
                $CatalogChannel = RatesChannelsModel::where('catalog_id','=',$catalogId)->where('channel_id','=',$channelId)->first();
                if(isset($requestArray[$channelId]) && !empty($CatalogChannel)){
                    $CatalogChannel->rate = $requestArray[$channelId];
                    $CatalogChannel->save();
                }else{
                    $obj = new RatesChannelsModel;
                    $obj->rate = $requestArray[$channelId];
                    $obj->catalog_id = $catalogId;
                    $obj->channel_id = $channelId;
                    $obj->save();
                }
            }
        }

        return redirect(route('catalog.index'))->with('alert', $this->alert('success', '操作成功!'));
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $channels = CatalogRatesModel::all();
        foreach ($channels as $channel){
            $channels_all[$channel->id] = $channel;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'channels_all' => $channels_all,
        ];
        return view($this->viewPath . 'edit', $response);
    }

    public function  exportExcel($rows,$name){
        Excel::create($name, function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }
    public function catalogCsvFormat(){
        $rows = [
            [
                '中文分类名称'=>'鞋子',
                '分类英文名称' => 'shoes',
                '前缀'=>'XL',
                'Set属性'=>'name1:value1,value2;name2:value1,value2',
                'variation属性'=>'name1:value1,value2;name2:value1,value2',
                'Feature属性(说明：1，文本；2，单选 ；3，多选 ) '=>'1-value;2-name1:value1,value2,value3;3-nname:value1,value2,value3',
            ]
        ];

        $channels = ChannelModel::all();
        foreach ($channels as $channel){
            $rows[0][$channel->name] = '20,40';
        }

        $this->exportExcel($rows, '批量添加产品品类csv格式');
    }
    public function addLotsOfCatalogs(){
        $file = request()->file('excel');

        $path = config('setting.excelPath');
        !file_exists($path.'excelProcess.xls') or unlink($path.'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        $data_array = '';
        $result = false;
        Excel::load($path.'excelProcess.xls', function($reader) use (&$result) {
            $reader->noHeading();
            $data_array = $reader->all()->toArray();

            $th_long = count($data_array[0]); //表头字段数
            for($i = 6 ; $i < $th_long ; $i++){
                $channels[$i] = $data_array[0][$i];
            }
            unset($data_array[0]); //去掉表头

            if($data_array[1][1] == 'shoes'){
                unset($data_array[1]); //去掉实例行
            }
                $insert_array =[];
                foreach ($data_array as $key => $item){
                    if(empty($item[0])){ //品类名中文名
                        $result = [
                            'info' => '品类中文名',
                            'id'   => $key
                        ];
                        break;
                    }
                    if(empty($item[1])){ //品类英文名
                        $result = [
                            'info' => '品类英文名',
                            'id'   => $key
                        ];
                        break;
                    }
                    $set = '';
                    $variation = '';
                    $feature = '';
                    if(!empty($item[3])){ //SET属性
                        $set_group = explode(';',trim($item[3]));
                        foreach ($set_group as $itemset){
                            $set_name_ary = '';
                            $tmp_arr = explode(':',$itemset);

                            if(count($tmp_arr) != 2){
                                $result = [
                                    'info' => 'Set属性格式错误',
                                    'id'   => $key
                                ];
                                break 2;
                            }
                            $name_tmp_ary = explode(',',$tmp_arr[1]);
                            foreach ($name_tmp_ary as $name_temp_value){
                                $set_name_ary[] = ['name' => $name_temp_value];
                            }
                            $set[] = [
                                'name'  => $tmp_arr[0],
                                'value' => ['name' => $set_name_ary],
                            ];
                        }
                    }
                    if(!empty($item[4])){ //variation属性
                        $set_group = explode(';',trim($item[4]));
                        foreach ($set_group as $item_var){
                            $var_name_ary = '';
                            $tmp_variation_arr = explode(':',$item_var);
                            if(count($tmp_variation_arr) != 2){

                                $result = [
                                    'info' => 'variation属性格式错误',
                                    'id'   => $key
                                ];
                                break 2;
                            }
                            $name_var_ary = explode(',',$tmp_variation_arr[1]);
                            foreach ($name_var_ary as $name_var_ary){
                                $var_name_ary[] = ['name' => $name_var_ary];
                            }
                            $variation[] = [
                                'name'  => $tmp_variation_arr[0],
                                'value' => ['name' => $var_name_ary],
                            ];

                        }
                    }

                    if(!empty($item[5])){ //Feature属性 包括单选 多选 文本 通过 type控制

                        $set_group = explode(';',trim($item[5]));
                        foreach ($set_group as $item_feature){
                            $feature_value_ary = explode(':',$item_feature);
                            $fea_type_name = explode('-',$feature_value_ary[0]);
                            if(count($feature_value_ary) == 1){//文本
                                $check = explode('-',$item_feature);
                                if(count($check) == 2){
                                    if(empty($check[0]) || empty($check[1]) ){
                                        $result = [
                                            'info' => 'Feature属性填写错误',
                                            'id'   => $key
                                        ];
                                        break 2;
                                    }
                                }elseif(!isset($fea_type_name[1]) || !isset($fea_type_name[0])){
                                    $result = [
                                        'info' => 'Feature属性填写错误',
                                        'id'   => $key
                                    ];
                                    break 2;
                                }
                                $fea_name_ary1[] = ['name' => ''];
                                $feature[] = [
                                    'name'  => $fea_type_name[1],
                                    'type'  => $fea_type_name[0],
                                    'value' => ['name' => $fea_name_ary1]
                                ];
                            }else{
                                if(empty($feature_value_ary[1]) || empty($fea_type_name[1]) || empty($fea_type_name[0])){
                                    $result = [
                                        'info' => 'Feature属性填写错误',
                                        'id'   => $key
                                    ];
                                    break 2;
                                }
                                $fea_name_ary2 = '';
                                foreach (explode(',',$feature_value_ary[1]) as $val){
                                    $fea_name_ary2[] = ['name' => $val];
                                }
                                $feature[] = [
                                    'name'  => $fea_type_name[1],
                                    'type'  => $fea_type_name[0],
                                    'value' => ['name' => $fea_name_ary2]
                                ];
                            }
                        }
                    }
                    //整合费率

                    $rates = '';
                    foreach ($channels as $key => $value){
                        $rates[$value] = $item[$key];
                    }
                    $insert_array[] = [
                        'c_name' => $item[0],
                        'name'   => $item[1],
                        'code'   => $item[2],
                        'channel_rate' => $rates,
                        'attributes' =>[
                            'sets'       => $set,
                            'variations' => $variation,
                            'features'   => $feature,
                        ],
                    ];
                }
                $result = $this->model->createLotsCatalogs($insert_array);
        },'gb2312');


        if($result){
            return redirect(route('catalog.index'))->with('alert', $this->alert('success', '批量插入成功!'));
        }else{
            if($result != false && isset($result['id']) && isset($result['info'])){
                $error_info = '操作失败,错误位置：<第'.$result['id'].'行>-<'.$result['info'].'>';
            }else{
                $error_info = '表格填写有误，请检查';
            }
            return redirect(route('catalog.index'))->with('alert', $this->alert('danger', $error_info));

        }


    }

    /**
     * 检查属性格式有效性
     * @param $AttributeAry
     * @param $type
     * return bool
     */
    public function doCheckAttribute($AttributeAry,$type){
        $result = TRUE;
        switch ($type){
            case 'set':
            case 'variation':
                $check_ary = explode(';',trim($AttributeAry));
                foreach ($check_ary as $item){
                    $result = strstr($item,':');
                    if($result == FALSE){
                        break;
                    }
                }
                break;
            default:
                return;
        }
        return $result;

    }
}
