<?php
/**
 * @modify Norton
 * @data 2016-6-20
 * @time 16:13:48
 */
namespace App\Http\Controllers;
use App\Models\Message\MessageModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use App\Models\Message\Template\TypeModel;
use App\Models\Message\AccountModel;
use App\Models\Message\Message_logModel;
use App\Models\Message\ReplyModel;
use App\Jobs\SendMessages;
use App\Models\Channel\AccountModel as Channel_account;
use Translation;
use Excel;
use App\Modules\Channel\Adapter\AliexpressAdapter;
use App\Modules\Channel\Adapter\WishAdapter;
use App\Modules\Channel\Adapter\EbayAdapter;
use App\Models\Message\SendEbayMessageListModel;
use App\Models\Order\ItemModel;
use App\Models\ChannelModel;
use Channel;


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
            'metas'             => $this->metas(__FUNCTION__),
            'data'              => $this->autoList($this->model,$this->model),
            'mixedSearchFields' => $this->model->mixed_search,
            'channel_accounts'  => Channel_account::all(),
            'users'             => $users,
            'channels'          => ChannelModel::All(),
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


            if($message->related == 0){
                $message->findOrderWithMessage();  //消息中的订单号 与 erp订单匹配
            }

            $emailarr=config('user.email');
           //dd($message->MessageInfo);
            //dd($message->ChannelParams);

            $response = [
                'metas' => $this->metas(__FUNCTION__),
                'message' => $message,
                'parents' => TypeModel::where('parent_id', 0)->get(),
                'users' => UserModel::all(),
                'emailarr' => $emailarr,
               // 'relatedOrders' => $message->related == 0 ? $message->guessRelatedOrders(request()->input('email')) : '',
                //'ordernum' =>$ordernum,
                'accounts'=>AccountModel::all(),
                'content'=>$message->MessageInfo,
                'driver' => $message->getChannelDiver(),
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
                return redirect(route('message.process',['id'=>$id]))
                    ->with('alert', $this->alert('success', '上条信息已标记稍后处理.'));
            }
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '处理成功.'));
        }
    }

    public function WishSupportReplay($id){
        $message = $this->model->find($id);
        if (!$message) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '信息不存在.'));
        }
        $account = Channel_account::find($message->account_id);

        $adapter = new WishAdapter($account->apiConfig);

        if($adapter->ReplayWishSupport($message->message_id)){
            $message->assign_id=request()->user()->id;
            $message->required=0;
            $message->status="COMPLETE";
            $message->save();
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '处理失败'));
        }
        if ($this->workflow == 'keeping') {
            return redirect(route('message.process'))
                ->with('alert', $this->alert('success', '上条信息已成功回复.'));
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '处理成功'));
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
/*            $account = $message->account;
            $reply = ReplyModel::where('message_id',$id)->get()->first();
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $channel->sendMessages($reply);*/

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
    public function show($id){
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

    /**
     * ajax获取百度翻译
     */
    public function ajaxGetTranInfo(){

            $content = request()->input('content');
            if(!empty($content)){
                $result = Translation::translate($content);
            }else{
                $result = false;
            }
       // echo json_encode(['content'=>'翻译结果','status'=>config('status.ajax.success')]);exit;
        if(isset($result['error_code'])){
                echo json_encode(['status'=>config('status.ajax.fail')]);
            }else{
                echo json_encode(['content'=>$result['trans_result'][0]['dst'],'status'=>config('status.ajax.success')]);
            }
    }
    /**
     * 速卖通批量留言（订单留言）
     */
    public function aliexpressReturnOrderMessages(){
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            //'rates' => $paypalRates->find(1), //获得paypal税
        ];

        return view('message.others.index',$response);

    }


    public function aliexpressCsvFormat(){
        $rows = [
            [
                'aliexpress orderID'=>'',
            ]
        ];

        $this->exportExcel($rows, 'smtCSV');
    }
    public function  exportExcel($rows,$name){
        Excel::create($name, function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }
    public function doSendAliexpressMessages(){
        $comments = request()->input('comments');
        $total = 0;
        $error_order_id = '';

        if(isset($_FILES['excel']['tmp_name']) && !empty($comments) ){
            $channel_orderids = Excel::load($_FILES['excel']['tmp_name'])->all()->toArray();
            if(is_array($channel_orderids)){
                foreach ($channel_orderids as $channel_orderid){
                    $channel_ordernum = (string)$channel_orderid['aliexpress_orderid'];
                    if(!empty($channel_ordernum)){
                        $order_obj = OrderModel::where('channel_ordernum','=',$channel_ordernum)->first();

                        if(!empty($order_obj)){
                            $Adapter = new AliexpressAdapter($order_obj->channelAccount->apiConfig);
                            $orderId = $order_obj->channel_ordernum;
                            $buyId   = $order_obj->aliexpress_loginId;
                            $result  = $Adapter->addMessageNew(compact('orderId','buyId','comments'));
                            $result ? $total += 1 : $error_order_id = $error_order_id.$orderId.';' ;
                        }
                    }
                }
            }
        }else{
            return redirect(route('aliexpressReturnOrderMessages'))->with('alert', $this->alert('danger', '文件和评论内容不能为空'));
        }
        return redirect(route('aliexpressReturnOrderMessages'))->with('alert', $this->alert('success', '成功发送'.$total.'条数据;失败条目aliexpress订单号:('.$error_order_id.')'));
    }
    
    public function SendEbayMessage(SendEbayMessageListModel $list){
        $form = request()->input();
        foreach($form as $key => $input){
            if(empty($input)){
                return redirect(route('order.index'))->with('alert',$this->alert('danger','参数不完整'.$key.'不能为空'));
            }
        }
        $order = OrderModel::find($form['message-order-id']);
        if($order->channel->driver){
            $ebay = new EbayAdapter($order->channelAccount->apiConfig);
            foreach ($form['item-ids'] as $item_id){
                if(!empty($item_id)){
                }
            }
            $buyer_id = $order['by_id'];
            $itemids  = $form['item-ids'];
            $title    = $form['message-title'];
            $content  = $form['message-content'];
           $is_send = $ebay->ebayOrderSendMessage(compact('item_id','buyer_id','itemids','title','content'));
           if($is_send){
               $list->operate_id = request()->user()->id;
               $list->order_id   = $form['message-order-id'];
               $list->title      = $form['message-title'];
               $list->content    = $form['message-content'];
               $list->itemids    = implode(',',$form['item-ids']);
               $list->save();
               return redirect(route('order.index'))->with('alert', $this->alert('success', '发送成功'));
           }else{
               return redirect(route('order.index'))->with('alert', $this->alert('danger', '发送失败'));
           }
        }
        return redirect(route('order.index'))->with('alert',$this->alert('发送失败，未知错误'));
    }

    public function ebayUnpaidCase(){
        $form = request()->input();
        if(empty($form['disputeType']) || empty($form['order_item_id'])){
            return redirect(route('order.index'))->with('alert', $this->alert('danger', '参数不完整'));

        }
        $order_item = ItemModel::find($form['order_item_id']);
        $ebay = new EbayAdapter($order_item->Order->channelAccount->apiConfig);

        $order_item_number = $order_item->orders_item_number;
        $transcation_id    = $order_item->transaction_id;
        $disputeType       = $form['disputeType'];
        if(!empty($order_item_number) || !empty($transcation_id) || !empty($disputeType)){
            $result = $ebay->ebayUnpaidCase(compact('order_item_number','transcation_id','disputeType'));
            if($result){
                $order_item->ebay_unpaid_status = 1;
                $order_item->save();
                return redirect(route('order.index'))->with('alert', $this->alert('success', '操作成功'));
            }
        }
        return redirect(route('order.index'))->with('alert', $this->alert('danger', '操作失败'));

    }


























}
