<?php
/**
 * 产品品类控制器
 * 产品品类CURD
 * @author: youjia
 * Date: 2015-12-28 17:57:09
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helps;
use App\Repositories\CatalogRepository;

class CatalogController extends Controller
{

    protected $catalog;

    public function __construct(Request $request, CatalogRepository $catalog)
    {
        $this->request = $request;
        $this->catalog = $catalog;
        $this->mainIndex = route('catalog.index');
        $this->mainTitle = '品类';
    }

    /**
     * 品类列表
     * 2015-12-18 14:53:01 YJ
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->request->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->catalog->auto()->paginate(),
        ];

        return view('catalog.index', $response);
    }

    /**
     * 新增分类界面
     * 2015-12-18 14:38:20 YJ
     * @return Illuminate\View\View Object
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view('catalog.create', $response);
    }

    /**
     * 保存品类
     * 2015-12-18 14:38:20 YJ
     * @return Illuminate\Http\RedirectResponse Object
     */
    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->catalog->rules('create'));             
        $data = $this->request->all();
        $extra['sets'] = $this->request->input('sets');
        $extra['attributes'] = $this->request->input('attributes');
        $extra['features'] = $this->request->input('features');
        $this->catalog->create($data,$extra);

        return redirect(route('catalog.index'));
    }

    /**
     * 品类查看
     * 2015-12-18 14:47:25 YJ
     * @param  int $id
     * @return Illuminate\View\View Object
     */
    public function show($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'catalog' => $this->catalog->get($id),
        ];

        return view('catalog.show', $response);

    }

    /**
     * 编辑品类
     * 2015-12-18 14:47:18 YJ
     * @param  int $id
     * @return Illuminate\View\View Object
     */
    public function edit($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'catalog' => $this->catalog->get($id),
        ];

        return view('catalog.edit', $response);
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
        $this->request->flash();
        $this->validate($this->request, $this->catalog->rules('update',$id));
        $data = $this->request->all();
        $extra['sets'] = $this->request->input('sets');
        $extra['attributes'] = $this->request->input('attributes');
        $extra['features'] = $this->request->input('features');
        $this->catalog->update($id,$data,$extra);

        return redirect(route('catalog.index'));
    }

    /**
     * 软删除品类
     * 2015-12-18 14:47:08 YJ
     * @param  int $id
     * @return Illuminate\Http\RedirectResponse Object
     */
    public function destroy($id)
    {
        $this->catalog->destroy($id);
        return redirect(route('catalog.index'));
    }
}
