<?php

/**
 * 选款需求控制器
 * 处理选款需求相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:21pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\productRequireRepository;

class productRequireController extends Controller
{
    protected $productRequire;

    function __construct(Request $request, productRequireRepository $productRequire)
    {
        $this->request = $request;
        $this->productRequire = $productRequire;
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
            'data' => $this->productRequire->auto()->paginate(),
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
            'productRequire' => $this->productRequire->get($id),
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
     * 文件移动
     * @param $fd file 文件指针
     * @param $name 转移后的文件名
     * @param $path 转移路径
     * @return 转以后的文件路径
     *
     */
    function move_file($fd, $name, $path)
    {
        file_exists($path) or mkdir($path, 644, true);
        if(file_exists($path.'/'.$name.substr($fd->getClientOriginalName(),strrpos($fd->getClientOriginalName(),'.'))))
            unlink($path.'/'.$name.substr($fd->getClientOriginalName(),strrpos($fd->getClientOriginalName(),'.')));
        $fd->move($path,$name.substr($fd->getClientOriginalName(),strrpos($fd->getClientOriginalName(),'.')));

        return "/".$path."/".$name.substr($fd->getClientOriginalName(),strrpos($fd->getClientOriginalName(),'.'));
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
        $this->validate($this->request, $this->productRequire->rules('create'));
        $data = $this->request->all();
        $buf = $this->productRequire->store($data);
        $data['id'] = $buf->id;
            
        for($i=1; $i <= 6; $i++) {
            if($this->request->hasFile('img'.$i)) {
                $file = $this->request->file('img'.$i);
                $path = Config('product_require_img_path.dir')."/".$data['id'];
                $dstname = $i;
                $absolute_path = $this->move_file($file, $dstname, $path);
                $name = 'img'.$i;
                $data["{$name}"] = $absolute_path;
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

        $this->validate($this->request, $this->productRequire->rules('update', $id));
        $data = $this->request->all();
        $buf = $this->productRequire->update($id, $data);
        
        for($i=1; $i <= 6; $i++) {
            if($this->request->hasFile('img'.$i)) {
                $file = $this->request->file('img'.$i);
                $path = Config('product_require_img_path.dir')."/".$id;
                $dstname = $i;
                $absolute_path = $this->move_file($file, $dstname, $path);
                $name = 'img'.$i;
                $data["{$name}"] = $absolute_path;
            }
        }
        $buf->update($data);

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
            'productRequire' => $this->productRequire->get($id),
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
        $this->productRequire->destroy($id);

        return redirect(route('productRequire.index'));
    }

}