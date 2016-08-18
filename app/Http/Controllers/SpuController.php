<?php
/**
 * spu管理控制器
 * @author: youjia
 * Date: 2016-8-2 10:46:32
 */
namespace App\Http\Controllers;

use App\Models\SpuModel;
use App\Models\UserModel;
use App\Models\ChannelModel;
use App\Models\Spu\SpuMultiOptionModel;

class SpuController extends Controller
{
    public function __construct(SpuModel $spu)
    {
        $this->model = $spu;
        $this->mainIndex = route('spu.index');
        $this->mainTitle = 'SPU列表';
        $this->viewPath = 'spu.';
    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        foreach(config('spu.status') as $key=>$value){
        	$num_arr[$key] = $this->model->where('status',$key)->count();
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'num_arr' =>$num_arr,
            'users' => UserModel::all(),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
    }

     /**
     * 分配任务
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dispatchUser()
    {
        $user_id = request()->input("user_id");
        $action = request()->input("action");
        $spu_ids = request()->input("spu_ids");
        $arr = explode(',', $spu_ids);
        foreach($arr as $id){
        	$this->model->find($id)->update([$action=>$user_id]);
        }	
        return 1;
    }

    /**
     * 处理任务
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function doAction()
    {
        $action = request()->input("action");
        $spu_ids = request()->input("spu_ids");
        $arr = explode(',', $spu_ids);
        foreach($arr as $id){
        	$this->model->find($id)->update(['status'=>$action]);
        }	
        return 1;
    }

    /**
     * 退回
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function actionBack()
    {
        $action = request()->input("action");
        $spu_ids = request()->input("spu_ids");
        $arr = explode(',', $spu_ids);
        foreach(config("spu.status") as $key=>$value){
            if($key==$action){
                $action = config("spu.status")[$key];break;
            }
            $prev_action = $key;
        }
        
        foreach($arr as $id){
            $this->model->find($id)->update(['status'=>$prev_action]);
        }   
        return 1;
    }

    /**
     * 保存备注
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function saveRemark()
    {
        $data = request()->all();
        $this->model->find($data['spu_id'])->update(['remark'=>$data['remark']]);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '备注添加成功'));
    }

    /**
     * 小语言编辑
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function spuMultiEdit()
    {

        $data = request()->all();
        $language = config('product.multi_language');
        $model = $this->model->find($data['id']);
        $default = $model->spuMultiOption->where("channel_id",ChannelModel::all()->first()->id)->first()->toArray();
        
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' =>$this->model->find($data['id']),
            'languages' => config('product.multi_language'),
            'channels' => ChannelModel::all(),
            'id' => $data['id'],
            'default' =>$default,
        ];
        return view($this->viewPath . 'language', $response);
    }

    /**
     * 小语言更新
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function spuMultiUpdate()
    {
        $data = request()->all();
        //echo '<pre>';
        //print_r($data);exit;
        $spuModel = $this->model->find($data['spu_id']);
        $spuModel->updateMulti($data);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '编辑成功.'));
    }

    /**
     * 批量更新
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function spuInfo()
    {
        $channel_id = request()->input("channel_id");
        $language = request()->input("language");
        $spu_id = request()->input("spu_id");
        $model = $this->model->find($spu_id);
        $info = $model->spuMultiOption->where("channel_id",(int)$channel_id)->first()->toArray();
        $result['name'] = $info[$language."_name"];
        $result['description'] = $info[$language."_description"];
        $result['keywords'] = $info[$language."_keywords"];
        return $result;
    }

    /**
     * 小语言
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function insertLan()
    {
        $spus = $this->model->all();

        foreach ($spus as $spu) {
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>1]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>2]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>3]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>4]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>5]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>6]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>7]);
            SpuMultiOptionModel::create(['spu_id'=>$spu->id,'channel_id'=>8]);
        }
    }

}
