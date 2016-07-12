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
        $channels = ChannelModel::all();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => $channels,
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
        $channels = ChannelModel::all();
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
        $channels = ChannelModel::whereIn('id',$channelIds)->get();
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
                $CatalogChannel = CatalogChannelsModel::where('catalog_id','=',$catalogId)->where('channel_id','=',$channelId)->first();
                if(isset($requestArray[$channelId])){
                    $CatalogChannel->rate = $requestArray[$channelId];
                    $CatalogChannel->save();
                }
            }
        }
        return redirect(route('catalog.index'))->with('alert', $this->alert('success', '操作成功!'));

    }
}
