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
}
