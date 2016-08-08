<?php
/**
 * @modify jiangdi
 * @data 2016-6-20
 * @time 16:13:48
 */
namespace App\Http\Controllers;
use App\Models\Message\MessageModel;
use App\Models\UserModel;
use App\Models\Message\Template\TypeModel;
use App\Models\Message\AccountModel;
use App\Models\Message\Message_logModel;
use App\Models\Message\ReplyModel;
use App\Jobs\SendMessages;


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
        //$userarr=config('user.staff');
        $users=UserModel::all();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model,$this->model->where('label', 'INBOX')),
            //'mixedSearchFields' => $this->model->mixed_search,
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
            $count = $this->model->where('from','=',$model->from)->where('status','<>','UNREAD')->count();
        }else{
            $count='';
        }
        
        if ($message->assign(request()->user()->id)) {
            //$userarr=config('user.staff');
            $emailarr=config('user.email');
            $response = [
                'metas' => $this->metas(__FUNCTION__),
                'message' => $message,
                'parents' => TypeModel::where('parent_id', 0)->get(),
                'users' => UserModel::all(),
                'emailarr' => $emailarr,
                'relatedOrders' => $message->related == 0 ? $message->guessRelatedOrders(request()->input('email')) : '',
                //'ordernum' =>$ordernum,
                'accounts'=>AccountModel::all(),
                'content'=>$message->MessageInfo,
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

        //return $model->message_content;  原来逻辑
        return $model->MessageInfo;
    }

    /**
     * 取消订单关联
     * @param $id
     * @param $relatedOrderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelRelatedOrder($id, $relatedOrderId)
    {
        $message = $this->model->find($id);
        if (!$message) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '信息不存在.'));
        }
        if ($message->cancelRelatedOrder($relatedOrderId)) {
            $alert = $this->alert('success', '取消订单关联成功.');
        } else {
            $alert = $this->alert('danger', '取消订单关联失败.');
        }
        return redirect(route('message.process', ['id' => $id]))->with('alert', $alert);
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

    /**
     * 转交给其他人
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignToOther($id)
    {
        $message = $this->model->find($id);
        $touser=UserModel::find(request()->input('assign_id'))->name;
        if (!$message) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '信息不存在.'));
        }
        if ($message->assignToOther(request()->user()->id ,request()->input('assign_id'))) {
            $data=array();
            $data['message_id']=$id;
            $data['foruser']=request()->user()->name;
            $data['assign_id']=request()->input('assign_id');
            $data['touser']=$touser;
            Message_logModel::create($data);
            if ($this->workflow == 'keeping') {
                return redirect(route('message.process'))
                    ->with('alert', $this->alert('success', '上条信息已转交他人.'));
            }
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '转交成功.'));
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('danger', '转交失败.'));
    }

    /**
     * 无需回复
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notRequireReply($id)
    {
        $message = $this->model->find($id);
        if (!$message) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '信息不存在.'));
        }
        if ($message->notRequireReply(request()->user()->id)) {
            if ($this->workflow == 'keeping') {
                return redirect(route('message.process'))
                    ->with('alert', $this->alert('success', '上条信息已标记无需回复.'));
            }
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '处理成功.'));
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('danger', '处理失败.'));
    }

    /**
     * 新增单个无需回复处理
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notRequireReply_1($id)
    {
        $message = $this->model->find($id);
        if($message->status!="COMPLETE"){
            $message->assign_id=request()->user()->id;
            $message->required=0;
            $message->status="COMPLETE";
            $message->save();
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '批量无需回复处理成功.'));
    }

    /**
     * 稍后处理
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function dontRequireReply($id)
    {
        $message = $this->model->find($id);
        if (!$message) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '信息不存在.'));
        }
        if ($message->dontRequireReply(request()->user()->id)) {
            if ($this->workflow == 'keeping') {
                return redirect(route('message.process1',['id'=>$id]))
                    ->with('alert', $this->alert('success', '上条信息已标记稍后处理.'));
            }
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '处理成功.'));
        }
    }

    /**
     * 关联订单
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setRelatedOrders($id)
    {
        $message = $this->model->find($id);
        if (!$message) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '信息不存在.'));
        }
        $numbers = request()->input('relatedOrdernums');
        if (request()->input('numbers')) {
            foreach (explode(',', request()->input('numbers')) as $number) {
                $numbers[] = $number;
            }
        }

        if ($message->setRelatedOrders($numbers)) {
            $alert = $this->alert('success', '关联订单成功.');
        } else {
            $alert = $this->alert('danger', '关联订单失败.');
        }
        return redirect(route('message.process', ['id' => $id]))->with('alert', $alert);
    }

    /**
     * 开启工作流
     * @return \Illuminate\Http\RedirectResponse
     */
    public function startWorkflow()
    {
        request()->session()->put('workflow', 'keeping');
        return redirect(route('message.process'))
            ->with('alert', $this->alert('success', '工作流已开启.'));
    }

    /**
     * 关闭工作流
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function endWorkflow($id)
    {
        request()->session()->pull('workflow');
        return redirect(route('message.process', ['id' => $id]))
            ->with('alert', $this->alert('danger', '工作流已终止.'));
    }

    public function reply($id, ReplyModel $reply)
    {
        $message = $this->model->find($id);
        if (!$message) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '信息不存在.'));
        }
        request()->flash();
        $this->validate(request(), $reply->rules('create')); //
        
        if ($message->reply(request()->all())) {
            /*
             * 写入队列
             */
            $reply = ReplyModel::where('message_id',$id)->get()->first();
            $job = new SendMessages($reply);
            $job = $job->onQueue('SendMessages');
            $this->dispatch($job);

            if ($this->workflow == 'keeping') {
                return redirect(route('message.process'))
                    ->with('alert', $this->alert('success', '上条信息已成功回复.'));
            }

            return redirect($this->mainIndex)->with('alert', $this->alert('success', '回复成功.'));
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('danger', '回复失败.'));
    }

    /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        //$sum=$this->model::all();

        $count = $this->model->where('from','=',$model->from)->where('status','=','UNREAD')->count();
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'count' => $count,
        ];
        return view($this->viewPath . 'show', $response)->with('count',$count);
    }


}
