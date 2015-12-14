<?php

/**
 * 选款需求控制器
 * 处理选款需求相关的Request与Response
 *
 * User: MC<178069409@qq.com>
 * Date: 15/12/4
 * Time: 13:49pm
 */

namespace App\Http\Controllers;

use Config;
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
     *
     * @ func 显示主界面
     * @ view/index 传参，columns和data
     *
     * @1:50pm
     *
     */
    public function index()
    {
        $this->request->flash();
        $response = [
            'data' => $this->productRequire->paginate(),
        ];

        return view('productRequire.index', $response);
    }

    /**
     *
     * @func   显示需求详细信息
     * @ view/show  传参response
     *
     * @ 1:50am
     *
     */

    public function show($id)
    {
        $response = [
            'productRequire' => $this->productRequire->detail($id),
        ];

        return view('productRequire.show', $response);
    }

    /**
     *
     * @func 创建记录
     * @retrun view/create
     *
     * @1:53pm
     *
     */
    public function create()
    {
        return view('productRequire.create');
    }


    /**
     *
     * @func 保存记录
     * @转移图片存储位置，返回路径
     *
     * @ 1:50pm
     *
     */
    public function store()
    {
        $this->request->flash();
        $rules = [
            'name' => 'required|max:255|unique:product_require,name',
            'competition_url' => 'active_url',
            'needer_id' => 'required',
            'needer_shop_id' => 'required',
        ];
        $this->validate($this->request, $rules);

        $data = [];

        $data['name'] = $this->request->input('name');
        $data['address'] = $this->request->input('province')." ".$this->request->input('city');
        $data['similar_sku'] = $this->request->input('sku');
        $data['competition_url'] = $this->request->input('url');
        $data['remark'] = $this->request->input('remark');
        $data['expected_date'] = $this->request->input('expdate');
        $data['needer_id'] = $this->request->input('needer_id');
        $data['needer_shop_id'] = $this->request->input('needer_shop_id');
        $data['created_by'] = $this->request->input('created_by');
        $data['status'] = '未处理';
        $data['user_id'] = NULL;
        $data['handle_time'] = NULL;

        $data['id'] = $this->productRequire->store($data);

        $path = '';
        $i=1;
        for( ; $i <= 6; $i++) {
            if($this->request->hasFile('img'.$i)) {
                $file = $this->request->file('img'.$i);
                $path = Config::get('product_require_img_path.dir')."/".$data['id'];
                file_exists($path) or mkdir($path, 644, true);
                $file->move($path,"/".$i.substr($file->getClientOriginalName(),strrpos($file->getClientOriginalName(),'.')));
                $name = 'img'.$i;
                $data["{$name}"] = "/".$path."/".$i.substr($file->getClientOriginalName(),strrpos($file->getClientOriginalName(),'.'));
            }
        }

        $this->productRequire->store($data);
        
        return redirect(route('productRequire.index'));
    }

    /*
    *
        @func 更新数据
        @param $id 数据的id

        @13:57pm
    *
    */

    public function update($id)
    {
        $this->request->flash();

        $data = [];
        $path = '';
        $i=1;
        for( ; $i <= 6; $i++) {
            if($this->request->hasFile('img'.$i)) {
                $file = $this->request->file('img'.$i);
                $path = Config::get('product_require_img_path.dir')."/".$id;
                file_exists($path) or mkdir($path, 644, true);
                if(file_exists($path.'/'.$i.substr($file->getClientOriginalName(),strrpos($file->getClientOriginalName(),'.'))))
                    unlink($path.'/'.$i.substr($file->getClientOriginalName(),strrpos($file->getClientOriginalName(),'.')));
                $file->move($path,$i.substr($file->getClientOriginalName(),strrpos($file->getClientOriginalName(),'.')));
                $name = 'img'.$i;
                $data["{$name}"] = "/".$path."/".$i.substr($file->getClientOriginalName(),strrpos($file->getClientOriginalName(),'.'));
            }
        }
        $data['name'] = $this->request->input('name');
        $data['address'] = $this->request->input('province')." ".$this->request->input('city');
        $data['similar_sku'] = $this->request->input('sku');
        $data['competition_url'] = $this->request->input('url');
        $data['remark'] = $this->request->input('remark');
        $data['expected_date'] = $this->request->input('expdate');
        $data['needer_id'] = $this->request->input('needer_id');
        $data['needer_shop_id'] = $this->request->input('needer_shop_id');
        $data['created_by'] = $this->request->input('created_by');
        $this->productRequire->update($id, $data);
        return redirect(route('productRequire.index'));
    }

    /*
    *
        @func 编辑需求
        @ param id 数据记录的id

        @return view/edit
        @1:50pm
    *
    */
    public function edit($id)
    {
        $response = [
            'productRequire' => $this->productRequire->detail($id),
        ];

        return view('productRequire.edit', $response);
    }

    /*
    *
        @func 删除一条记录
        @param  $id  记录的id

        @return view/destroy
    *
    */

    public function destroy($id)
    {
        $this->fashion->destroy($id);

        return redirect(route('productRequire.index'));
    }

}