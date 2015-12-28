<?php

/**
 * 选款需求控制器
 * 处理选款需求相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:21pm
 */

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Product\RequireRepository;

class RequireController extends Controller
{
    protected $require;

    public function __construct(Request $request, RequireRepository $require)
    {
        $this->request = $request;
        $this->require = $require;
    }

    /**
     * 列表页显示
     *
     * @param none
     * @return view
     *
     */
    public function index()
    {
        $this->request->flash();
        $response = [
            'data' => $this->require->auto()->paginate(),
        ];

        return view('product.require.index', $response);
    }

    /**
     * 详情页
     *
     * @param $id integer 记录id
     * @return view
     *
     */

    public function show($id)
    {
        $response = [
            'require' => $this->require->get($id),
        ];

        return view('product.require.show', $response);
    }

    /**
     * 跳转创建页面
     *
     * @param none
     * @return view
     *
     */
    public function create()
    {
        return view('product.require.create');
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
        $this->validate($this->request, $this->require->rules('create'));
        $data = $this->request->all();
        $buf = $this->require->create($data);
        $data['id'] = $buf->id;

        for ($i = 1; $i <= 6; $i++) {
            if ($this->request->hasFile('img' . $i)) {
                $file = $this->request->file('img' . $i);
                $path = config('product_require_img_path.dir') . "/" . $data['id'];
                $dstname = $i;
                $absolute_path = $this->require->move_file($file, $dstname, $path);
                $name = 'img' . $i;
                $data[$name] = $absolute_path;
            }
        }
        $buf->update($data);

        return redirect(route('productRequire.index'));
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

        $this->validate($this->request, $this->require->rules('update', $id));
        $data = $this->request->all();

        for ($i = 1; $i <= 6; $i++) {
            if ($this->request->hasFile('img' . $i)) {
                $file = $this->request->file('img' . $i);
                $path = config('product_require_img_path.dir') . "/" . $id;
                $dstname = $i;
                $absolute_path = $this->require->move_file($file, $dstname, $path);
                $name = 'img' . $i;
                $data["{$name}"] = $absolute_path;
            }
        }
        $this->require->update($id, $data);

        return redirect(route('productRequire.index'));
    }

    /**
     * 跳转页面更新页
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function edit($id)
    {
        $response = [
            'require' => $this->require->get($id),
        ];

        return view('product.require.edit', $response);
    }

    /**
     * 删除一条记录
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function destroy($id)
    {
        $this->require->destroy($id);

        return redirect(route('productRequire.index'));
    }

}