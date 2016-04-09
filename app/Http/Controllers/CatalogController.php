<?php
/**
 * 产品品类控制器
 * 产品品类CURD
 * @author: youjia
 * Date: 2015-12-28 17:57:09
 */

namespace App\Http\Controllers;

use App\Models\CatalogModel;

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
        $this->model->createCatalog($data,$extra);
        return redirect($this->mainIndex);
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
        $catalogModel->updateCatalog($data,$extra);
        return redirect($this->mainIndex);
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
        return redirect(route('catalog.index'));
    }
}
