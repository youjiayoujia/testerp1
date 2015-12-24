<?php
/**
 * 库存调整控制器
 * 处理入库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/24
 * Time: 14:22
 */

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Stock\AdjustmentRepository;

class AdjustmentController extends Controller
{
    protected $adjustment;

    public function __construct(Request $request, AdjustmentRepository $adjustment)
    {
        $this->adjustment = $adjustment;
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
            'data' => $this->adjustment->auto()->paginate(),
        ];

        return view('stock.adjustment.index', $response);
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
            'adjustment' => $this->adjustment->get($id),
        ];

        return view('stock.adjustment.show', $response);
    }

    /**
     * 跳转创建页 
     *
     * @param none
     * @return view
     *
     */
    public function create()
    {
        return view('stock.adjustment.create');
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

        $this->validate($this->request, $this->adjustment->rules('create'));
        $this->adjustment->create($this->request->all());

        return redirect(route('adjustment.index'));
    }

    /**
     * 跳转数据编辑页 
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function edit($id)
    {
        $response = [
            'adjustment' => $this->adjustment->get($id),
        ];

        return view('stock.adjustment.edit', $response);
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
        $this->validate($this->request, $this->adjustment->rules('update'));
        $this->adjustment->update($id, $this->request->all());

        return redirect(route('adjustment.index'));
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
        $this->adjustment->destroy($id);
        return redirect(route('adjustment.index'));
    }
}