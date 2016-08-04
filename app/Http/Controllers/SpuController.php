<?php
/**
 * spu管理控制器
 * @author: youjia
 * Date: 2016-8-2 10:46:32
 */
namespace App\Http\Controllers;

use App\Models\SpuModel;
use App\Models\UserModel;


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

}
