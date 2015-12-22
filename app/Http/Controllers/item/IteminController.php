<?php
/**
 * 入库控制器
 * 处理入库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/22
 * Time: 10:45am
 */

namespace App\Http\Controllers\Item;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Item\IteminRepository;
use App\Models\Item\IteminNameModel as iteminName;

class IteminController extends Controller
{
    protected $itemin;

    function __construct(Request $request, IteminRepository $itemin)
    {
        $this->itemin = $itemin;
        $this->request = $request;
    }

    /**
    * 列表显示页
    *
    * @param none
    * @return view
    *
    */
    public function index()
    {
        $this->request->flash();

        $response = [
            'data' => $this->itemin->auto()->paginate(),
            'iteminname' => config('iteminname'),
        ];

        return view('item.in.index', $response);
    }

    /**
     * 信息详情页 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function show($id)
    {
        $response = [
            'itemin' => $this->itemin->get($id),
        ];

        return view('item.in.show', $response);
    }

    /**
     * 跳转创建页 
     *
     * @param none
     * @return view
     *
     */
    public function create(iteminName $iteminname)
    {
        $response = [
            'data' => $iteminname->all(),
        ];

        return view('item.in.create', $response);
    }

    /**
     * 数据保存 
     *
     * @param none
     * @return view
     *
     */
    public function store()
    {
        $this->request->flash();

        $this->validate($this->request, $this->itemin->rules('create'));
        $this->itemin->create($this->request->all());

        return redirect(route('itemin.index'));
    }

    /**
     * 跳转数据编辑页 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function edit($id, iteminName $iteminname)
    {
        $response = [
            'data' => $iteminname->all(),
            'itemin' => $this->itemin->get($id),
        ];

        return view('item.in.edit', $response);
    }

    /**
     * 数据更新 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->itemin->rules('update', $id));
        $this->itemin->update($id, $this->request->all());

        return redirect(route('itemin.index'));
    }

    /**
     * 记录删除 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function destroy($id)
    {
        $this->itemin->destroy($id);
        return redirect(route('itemin.index'));
    }
}