<?php
/**
 * @author 姜笛
 * @data 2016-6-20
 * @time 16:13:48
 */
namespace App\Http\Controllers;
use App\Models\Message\MessageModel;
use App\Models\UserModel;
use App\Models\Message\Template\TypeModel;
use App\Models\Message\AccountModel;

class MessageController extends Controller
{
    public function __construct(MessageModel $message)
    {
        $this->model = $message;
        $this->mainIndex = route('message.index');
        $this->mainTitle = '信息';
        $this->viewPath = 'message.';
        $this->workflow = request()->session()->get('workflow');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        request()->flash();
        $userarr=config('user.staff');
        $users=UserModel::whereIn('id', $userarr)->get();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->where('label', 'INBOX')),
            'users' => $users,
        ];
        return view($this->viewPath . 'index', $response);
    }

    public function process(){

        if (request()->input('id')) {
            $message = $this->model->find(request()->input('id'));
        } elseif ($this->workflow == 'keeping') {
            $message = $this->model->getOne(request()->user()->id);
        } else {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', 'error.'));
        }
        if (!$message) {
              return redirect($this->mainIndex)->with('alert', $this->alert('danger', '信息不存在.'));
        }
        if(request()->input('id')){
            $model = $this->model->find(request()->input('id'));
            $count = $this->model->where('from','=',$model->from)->where('status','=','UNREAD')->count();
        }else{
            $count='';
        }

        
        if ($message->assign(request()->user()->id)) {
            $userarr=config('user.staff');
            $emailarr=config('user.email');
            $response = [
                'metas' => $this->metas(__FUNCTION__),
                'message' => $message,
                'parents' => TypeModel::where('parent_id', 0)->get(),
                'users' => UserModel::whereIn('id', $userarr)->get(),
                'emailarr' => $emailarr,
                'relatedOrders' => $message->related == 0 ? $message->guessRelatedOrders(request()->input('email')) : '',
                //'ordernum' =>$ordernum,
                'accounts'=>AccountModel::all(),
            ];

            return view($this->viewPath . 'process', $response)->with('count',$count);

        }
        return redirect($this->mainIndex)->with('alert', $this->alert('danger', '该信息已被他人处理.'));

    }

    public function content($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }

        return $model->message_content;
    }

    /**
     * 无需关联订单
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notRelatedOrder($id)
    {
        $message = $this->model->find($id);
        if (!$message) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '信息不存在.'));
        }
        if ($message->notRelatedOrder()) {
            $alert = $this->alert('success', '无需关联订单设置成功.');
        } else {
            $alert = $this->alert('danger', '无需关联订单设置失败.');
        }
        return redirect(route('message.process', ['id' => $id]))->with('alert', $alert);
    }



}
