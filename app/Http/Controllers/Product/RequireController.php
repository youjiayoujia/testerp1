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

use App\Http\Controllers\Controller;
use App\Models\Product\RequireModel;
use App\Models\CatalogModel;
use App\Models\ChannelModel;
use App\Models\Channel\AccountModel;
use App\Models\SpuModel;
use Tool;

class RequireController extends Controller
{
    public function __construct(RequireModel $require)
    {
        $this->model = $require;
        $this->mainIndex = route('productRequire.index');
        $this->mainTitle = '选款需求';
        $this->viewPath = 'product.require.';
    }

    /**
     * 新建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'catalogs' => CatalogModel::all(),
            'channel' =>ChannelModel::all(),
            'channel_account' => AccountModel::where("channel_id",'=',1)->get(),
        ];

        return view($this->viewPath . 'create', $response);
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
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $buf = $this->model->create($data);
        $data['id'] = $buf->id;
        $data['created_by'] = request()->user()->id;
        for ($i = 1; $i <= 6; $i++) {
            if (request()->hasFile('img' . $i)) {
                $file = request()->file('img' . $i);
                $path = config('product.requireimage') . "/" . $data['id'];
                $dstname = $i;
                $absolute_path = $this->model->move_file($file, $dstname, $path);
                $data['img'.$i] = $absolute_path;
            }
        }
        $buf->update($data);

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '保存成功'));
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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'catalogs' => CatalogModel::all(),
            'channel' =>ChannelModel::all(),
            'channel_account' => AccountModel::where("channel_id",$model->needer_id)->get(),
        ];
        
        return view($this->viewPath . 'edit', $response);
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
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $model->rules('update', $id));
        $data = request()->all();
        for ($i = 1; $i <= 6; $i++) {
            if (request()->hasFile('img' . $i)) {
                $file = request()->file('img' . $i);
                $path = config('product.requireimage') . "/" . $id;
                $dstname = $i;
                $absolute_path = $this->model->move_file($file, $dstname, $path);
                $data['img'.$i] = $absolute_path;
            }
        }
        $model->update($data);

        return redirect($this->mainIndex)->with('alert', $this->alert('success', '修改成功'));
    }

    /**
     * ajax 处理请求 
     *
     * @return json
     *
     */
    public function ajaxProcess()
    {
        $id = request()->input('id');
        $status = request()->input('status');
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        
        if($status == 1) {
            $model->update(['status'=>'1', 'handle_id'=>'1', 'handle_time'=>date('Y-m-d h:i:s')]);
        } else {
            $catalog = CatalogModel::find($model->catalog_id);
            $code_num = SpuModel::where("spu", "like", $catalog->code . "%")->get()->count();
            //创建spu，,并插入数据
            $spuobj = SpuModel::create(['spu' => Tool::createSku($catalog->code, $code_num),'product_require_id'=>$model->id,'status'=>'purchase','purchase'=>$model->purchase_id,'developer'=>$model->created_by]);
            $model->update(['status'=>'2', 'handle_id'=>'1', 'handle_time'=>date('Y-m-d h:i:s')]);
            $channels = ChannelModel::all();
            foreach ($channels as $channel) {
                $spuobj->spuMultiOption()->create(['spu_id'=>$spuobj->id,'channel_id'=>$channel->id]);
            }    
        }

        return json_encode($status);
    }

    /**
     * ajax 批量处理请求 
     *
     * @return json
     *
     */
    public function ajaxQuantityProcess()
    {
        $buf = request()->input('buf');
        $status = request()->input('status');
        foreach($buf as $v)
        {
            $model = $this->model->find($v);
            if($model->status) {
                continue;
            }
            $model->update(['status'=>$status, 'handle_id'=>'1', 'handle_time'=>date('Y-m-d h:i:s')]);
        }

        return json_encode('success');
    }
}