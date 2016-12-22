<?php
/**
 * User: Norton
 * Date: 2016/6/20
 * Time: 19:04
 */
namespace App\Models\Message;
use App\Base\BaseModel;
use App\Models\PackageModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use Tool;
use Translation;
use App\Models\Channel\AccountModel as Channel_Accounts;
use App\Models\ChannelModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
//use App\Models\Order\PackageModel;
class MessageModel extends BaseModel{
    protected $table = 'messages';

    protected $fillable = [
        'account_id',
        'message_id',
        'mime_type',
        'from',
        'from_name',
        'to',
        'date',
        'subject',
        'start_at',
        'content',
        'title_email',
        'label',
    ];

    public $searchFields = [
        'id'=>'ID',
        'subject'=>'主题',
        'from'=>'发件邮箱' ,
        'label' => '消息类型',
        'channel_order_number' => '平台订单号'
    ];

    public $rules = [];

    public $appends = [
        'channel_diver_name',
        'msg_time'
    ];

  public function account()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel');
    }
    public function assigner()
    {
        return $this->belongsTo('App\Models\UserModel', 'assign_id' , 'id');
    }

    public function getLabelTextAttribute()
    {
        $result = "<span class='label label-success'>$this->label</span>";
        return $result;
    }

    public function getStatusTextAttribute()
    {
        return config('message.statusText.' . $this->status);
    }

    public function relatedOrders()
    {
        return $this->hasMany('App\Models\Message\OrderModel', 'message_id');
    }

    public function channel(){
        return $this->hasOne('App\Models\ChannelModel','id','channel_id');
    }

    public function Order(){
        return $this->belongsTo('App\Models\OrderModel', 'channel_order_number','channel_ordernum');

    }
    public function replies()
    {
        return $this->hasMany('App\Models\Message\ReplyModel', 'message_id');
    }

    public function parts()
    {
        return $this->hasMany('App\Models\Message\PartModel', 'message_id');
    }
    public function getAttachment()
    {
        return $this->hasMany('App\Models\Message\MessageAttachment', 'message_id');
    }

    public function getChannelNameAttribute(){
        if(!empty($this->channel_id)){
            return $this->channel->name;
        }else{
            return '无';
        }
    }

    /**
     * 更多搜索
     * @return array
     */
    public function getMixedSearchAttribute()
    {
        //dd(UserModel::all()->pluck('name','name'));
        return [
            'relatedSearchFields' => [],
            'filterFields' => [
                'from_name',
                'from',

            ],
            'filterSelects' => [
                'messages.status' => config('message.statusText'),
                'assign_id' => UserModel::all()->pluck('name','id'),
            ],
            'selectRelatedSearchs' => [
                'channel' => ['name' => ChannelModel::all()->pluck('name', 'name')],
                'account' => ['account' => Channel_Accounts::all()->pluck('account', 'account')],
                //'assigner' => ['name' => UserModel::all()->pluck('name','name')],
            ],
            'sectionSelect' => ['time'=>['created_at']],
        ];
    }

    /**
     * 分配
     * @param $userId
     * @return bool
     */
    public function assign($userId)
    {
        switch ($this->status) {
            case 'UNREAD':
                $this->assign_id = $userId;
                $this->status = 'PROCESS';
                return $this->save();
                break;
            default:
                return $this->assign_id == $userId;
                break;
        }
    }

    public function getUserAccountIDs($userId){
        if($userId) {
            $accounts = AccountModel::where('customer_service_id', '=', $userId)->get();
            if (count($accounts) <> 0) {
                foreach ($accounts as $key => $account) {
                    $ids_ary[] = $account->id;
                }
                return $ids_ary;
            }
        }
        return false;
    }

    public function assignToOther($fromId, $assignId)
    {

        $assignUser = UserModel::find($assignId);
        if ($assignUser) {
            $this->assign_id = $assignId;
            return $this->save();
        }

        return false;
    }

    public function getHistoriesAttribute()
    {
        return MessageModel::where('from','=', $this->from)
            ->where('id', '<>', $this->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function getMsgTimeAttribute(){
        if(! empty($this->date)){
            return Carbon::parse($this->date)->diffForHumans();
        }else{
            return '';
        }
    }

    public function getMessageContentAttribute()
    {
        $plainBody = '';
        foreach ($this->parts as $part) {
            if ($part->mime_type == 'text/html') {
                $htmlBody = Tool::base64Decode($part->body);
                $htmlBody=preg_replace("/<(\/?body.*?)>/si","",$htmlBody);
            }
            if ($part->mime_type == 'text/plain') {
                $plainBody .= nl2br(Tool::base64Decode($part->body));
            }
        }
        $body = isset($htmlBody) && $htmlBody != '' ? $htmlBody : $plainBody;
        
        return $body;
    }

    public function cancelRelatedOrder($relatedOrderId)
    {
        $relatedOrder = $this->relatedOrders()->find($relatedOrderId);
        if ($relatedOrder) {
            $relatedOrder->delete();
            if ($this->relatedOrders()->count() < 1) {
                $this->related = 0;
                $this->save();
            }
            return true;
        }
        return false;
    }

    public function notRelatedOrder()
    {
        $this->related = 1;
        return $this->save();
    }

    public function notRequireReply($userId)
    {
            $this->required = 0;
            $this->status = 'COMPLETE';
            if($this->save()){
                return true;
            }else{
                return false;

            }
    }

    public function dontRequireReply($userId)
    {
        if ($this->assign_id == $userId) {
            $this->required = 0;
            $this->status = 'PROCESS';
            $this->dont_reply = 1;
            return $this->save();
        }
        return false;
    }
    
    public function setRelatedOrders($numbers)
    {
        if ($numbers) {
            foreach ($numbers as $number) {
                
                $order = OrderModel::ofOrdernum($number)->first();
                if ($order) {
                    $this->relatedOrders()->create(['order_id' => $order->id]);
                } else {
                    $package = PackageModel::ofTrackingNo($number)->first();
                    if ($package) {
                        $this->relatedOrders()->create(['order_id' => $package->order_id]);
                    }
                }
            }
            if ($this->relatedOrders()->count() > 0) {
                $this->related = 1;
                $this->start_at = date('Y-m-d H:i:s', time());
                return $this->save();
            }
        }
        return false;
    }

    /**
     * 回复
     * @param $data
     * @return bool
     */
    public function reply($data)
    {
        $data['to_email'] = trim($data['to_email']);
        $data['status'] = 'NEW';
        if ($this->replies()->create($data)) {
            //记录回复邮件类型
            $this->type_id = 0;
            $this->status = 'COMPLETE';
            $this->end_at = date('Y-m-d H:i:s', time());
            return $this->save();
        }
        return false;
    }

    public function getMessageAttanchmentsAttribute()
    {
        $attanchments = [];
        foreach ($this->getAttachment as $key => $part) {
            if ($part->filename) {
                $attanchments[$key]['filename'] = $part->filename;
                $attanchments[$key]['filepath'] = '/' . config('message.attachmentSrcPath') .$part->filepath;
            }
        }
        return $attanchments;
    }

    //获取用户所有提问内容
    public function getUserMsgInfoAttribute(){
        $content_string = false;
        //dd($this->from_name);
        $message_info = $this->ContentDecodeBase64;
        if(! empty($message_info)){
            foreach ($message_info as $key => $content){
                switch ($key) {
                    case 'aliexpress':
                        foreach($content->result as $item){
                            if($this->from_name == $item->senderName){
                                $content_string .= $item->content;
                            }
                        }

                        break;
                    case 'wish':
                       foreach ($content as $k => $item){
                            if($item['Reply']['sender'] == 'user') {
                                $content_string .= $item['Reply']['message'];
                            }
                        }
                        break;
                    default:
                        $content_string = false;
                }
            }
        }
        return $content_string;
    }

    public function IsFristMsgForOrder(){
        $message_info = $this->ContentDecodeBase64;
        $result = false;
        foreach ($message_info as $channel_name => $content){
            switch ($channel_name){
                case 'aliexpress':
                    $content_group = Collection::make($content->result)->groupBy('senderName');
                    if($content_group->count() == 1){ //只存在用户信息
                        $result = true;
                    } else {
                        foreach ($content_group as $key => $item){
                            if($key != $this->from_name){ //包含自动去信的第一个消息
                                if($item->count() == 1){
                                    $result = true;
                                }
                            }
                        }

                    }
                    break;
                case 'wish':
                    $hasMerchant =  Collection::make($content)->flatten()->search('merchant');
                    if(! $hasMerchant){
                        $result = true;
                    }
                    break;

                case 'ebay':

                    break;
                default:




            }
        }
        return $result;
    }

    public function MsgOrderIsExpress(){
        if($order = $this->relatedOrders()->first()){
            $package = OrderModel::find($order->order_id)->packages()->first();
            if(! empty($package)){
                if($package->logistics->is_express == '0'){
                    return true;
                }
            }
        }
        return false;
    }

    public function getMessageInfoAttribute(){
        if($this->ContentDecodeBase64){
            $html = '';
            foreach($this->ContentDecodeBase64 as $key => $content){
                switch ($key){
                    case 'wish':
                        foreach ($content as $k => $item){
                           if(!empty($item['Reply']['message'])){
                               if($item['Reply']['sender'] != 'merchant'){
                                   if($item['Reply']['sender'] == 'wish support'){
                                       $this->from_name = $item['Reply']['sender'];
                                   }
                                   $html .= '<div class="alert alert-warning col-md-10" role="alert"><p><strong>Sender：</strong>'.$this->from_name.':</p><strong>Content: </strong>'.$item['Reply']['message'];
                                   $html .= '<p class="time"><strong>Time：</strong>'.$item['Reply']['date'].'</p>';

                                   if(isset($item['Reply']['translated_message']) && isset($item['Reply']['translated_message_zh'])){
                                       $html .= '<div class="alert-danger"><strong>Wish翻译: </strong><p>'.$item['Reply']['translated_message'].'</p><p>'. $item['Reply']['translated_message_zh'].'</p></div>';
                                   }else{

                                   }

                                   $html .= '</div>';
                               }else{
                                   $html .= '<div class="alert alert-success col-md-10" role="alert" style="float: right"><p><strong>用户名：</strong>'.$item['Reply']['sender'].':</p><strong>Content: </strong>'.$item['Reply']['message'];
                                   $html .= '<p class="time"><strong>Time：</strong>'.$item['Reply']['date'].'</p>';
                                   $html .= '</div>';
                               }
                           }
                        }
                        break;
                    case 'aliexpress':
                        $message_content = array_reverse($content->result); //逆序
                        $product_html = '';
                        $message_fields_ary = false;
                        foreach ($message_content as $k => $item){

                            if($message_fields_ary == false && $item->messageType == 'product'){
                                $message_fields_ary['product_img_url']      = isset($item->summary->productImageUrl) ? $item->summary->productImageUrl : '';
                                $message_fields_ary['product_product_url']  = isset($item->summary->productDetailUrl) ? $item->summary->productDetailUrl : '';
                                $message_fields_ary['product_product_name'] = isset($item->summary->productName) ? $item->summary->productName : '';

                                $product_html .= '<div class="col-lg-12 alert-default">';
                                $product_html .= '<table class="table table-bordered table-striped table-hover sortable">';
                                $product_html .= '<tr>';
                                $product_html .= '<th>产品图片</th>';
                                $product_html .= '<th>产品名称</th>';
                                $product_html .= '<th>产品连接</th>';
                                $product_html .= '</tr>';
                                $product_html .= '<tr>';
                                $product_html .= '<td><img src ="'.$message_fields_ary['product_img_url'] .'"/></td>';
                                $product_html .= '<td>'.$message_fields_ary['product_product_name'] .'</td>';
                                $product_html .= '<td><a target="_blank" href="'.$message_fields_ary['product_product_url'].'">链接</a></td>';
                                $product_html .= '</tr>';
                                $product_html .= '</table>';
                                $product_html .= '</div>';

                            }

                            //dd($message_fields_ary);
                            $row_html = '';
                            if($item->content == '< img >'){
                                foreach ($item->filePath as $item_path){
                                    if($item_path->mPath){
                                        $row_html .='<img src="'.$item_path->mPath.'" /><a href="'.$item_path->lPath.'" target="_blank">查看大图</a>';
                                    }
                                }
                            }
                            $content = $item->content;
                            $content = str_replace("&nbsp;", ' ', $content);
                            $content = str_replace("&amp;nbsp;", ' ', $content);
                            $content = str_replace("&amp;iquest;", ' ', $content);
                            $content = str_replace("\n", "<br />", $content);
                            $content = preg_replace("'<br \/>[\t]*?<br \/>'", '', $content);
                            $content = str_replace("/:000", '<img src="http://i02.i.aliimg.com/wimg/feedback/emotions/0.gif" />', $content);
                            $content = preg_replace("'\/\:0+([1-9]+0*)'", "<img src='http://i02.i.aliimg.com/wimg/feedback/emotions/\\1.gif' />", $content);
                            $content = (stripslashes(stripslashes($content)));

                            $datetime = date('Y-m-d H:i:s',$item->gmtCreate/1000);
                            if($this->from_name != $item->summary->receiverName){
                                if($row_html != ''){
                                    $html .= '<div class="alert alert-warning col-md-10" role="alert"><p><strong>Sender: </strong>'.$item->senderName.':</p><strong>Content: </strong>'.$row_html;
                                    $html .= '<p class="time"><strong>Time: </strong>'.$datetime.'</p>';
                                    $html .= '</div>';
                                }else{
                                    $html .= '<div class="alert alert-warning col-md-10" role="alert"><p><strong>Sender: </strong>'.$item->senderName.':</p><strong>Content: </strong>'.$content;
                                    $html .= '<p class="time"><strong>Time: </strong>'.$datetime.'</p>';
                                    $html .= '<button style="float: right;" type="button" class="btn btn-success btn-translation" need-translation-content="'.$content.'" content-key="'.$k.'">
                                    翻译
                                </button>
                                <p id="content-'.$k.'" style="color:green"></p>';
                                    $html .= '</div>';
                                }

                            }else{
                                $html .= '<div class="alert alert-success col-md-10" role="alert" style="float: right"><p><strong>Sender: </strong>'.$item->senderName.':</p><strong>Content: </strong>'.$content;
                                $html .= '<p class="time"><strong>Time: </strong> '.$datetime.'</p>';
                                $html .= '</div>';
                            }
                        }
                        break;

                    case 'ebay':
                        $html = $content;
                        break;
                    case 'amazon':
                        $html = $content;
                        break;
                    default :
                        $html = 'invaild channel message';
                }
            }

            return empty($product_html) ? $html : $product_html.$html;
        }else{
            return '';
        }
    }

    //渠道信息特殊属性
    public function getMessageFieldsDecodeBase64Attribute(){
        if($this->channel_message_fields){
            return unserialize(base64_decode($this->channel_message_fields));
        }else{
            return '';
        }
    }
    public function getContentDecodeBase64Attribute(){
        if($this->content){
            return unserialize(base64_decode($this->content));
        }else{
            return '';
        }
    }
    /**
     * 获取消息对应的渠道
     * @return mixed
     */
    public function getChannelDiver(){
        return $this->account->channel->driver;
    }

    public function getChannelDiverNameAttribute ()
    {
        return !empty($this->account->channel->driver) ? $this->account->channel->driver : false;
    }

    public function findOrderWithMessage(){
        $order_id = $this->getChannelMessageOrderId(); //根据平台参数获取关联订单号
        if(!empty($order_id)){
            //$order_obj = OrderModel::where('channel_ordernum','=',$order_id)->first();
            if(!empty($order_id)){
                if($this->relatedOrders()->create(['order_id' => $order_id])){
                    $this->related = 1;
                    $this->save();
                }
            }
        }

    }

    public function getChannelMessageOrderId(){
        $fields_ary = $this->MessageFieldsDecodeBase64;
        switch ($this->getChannelDiver()){
            case 'ebay':
                $order_id = $fields_ary['ItemID'];
                if(!empty($order_id)){
                    $order_obj = OrderModel::where('transaction_number','=',$order_id)->first();
                    $order_id = empty($order_obj) ? '' : $order_obj->id;
                }else{
                    $order_id = '';
                }
                break;
            case 'wish':
                 //wish交易号
                $order_obj = OrderModel::where('transaction_number','=',$this->channel_order_number)->first();
                $order_id = empty($order_obj) ? '' : $order_obj->id;   //根据 orders 表 交易号
                break;
            case 'aliexpress':
                $order_id = !empty($this->Order->id) ? $this->Order->id : '';
                break;
            default:
                $order_id = '';
        }

        return $order_id;
    }

    /**
     * 渠道参数信息
     */
    public function getChannelParamsAttribute(){
        $html = '';
        $channel = $this->getChannelDiver();
        switch ($channel){
            case 'aliexpress':
                $html .= '<span class="label label-warning">'.$this->label.'</span>';
                break;
            case 'wish':
                $files = $this->MessageFieldsDecodeBase64;

                $language = ! empty(config('message.wish')['country'][$this->country]) ? config('message.wish')['country'][$this->country] : '未知';

                if($files){
                    $html .= '<p><strong>Transaction id</strong>:'.$files['order_items'][0]['Order']['transaction_id'].'</p>';
                    $html .= '<p><strong>语言</strong>:'.$language.'</p>';
                }else{
                    $html .= '<p>暂无</p>';
                }
                break;
            case 'ebay':
                $files = $this->MessageFieldsDecodeBase64;
                if(!empty($files)){
                    $html .= '<p><strong>ItemID</strong>:'.$files['ItemID'].'</p>';
                    $html .= '<p><strong>Ebay链接</strong>:<a target="_blank" href="'.$files['ResponseDetails'].'"><span class="glyphicon glyphicon-link"></span></a></p>';

                }

                break;

            default:
                $html = '';
        }

        return $html;
    }

    public function getMessageAccountNameAttribute()
    {
       $obj = $this->account;
        if(!empty($obj)){
            return  $obj->account;
        }else{
            return '平台账号';
        }
    }

    public function getMyWorkFlowMsg ($entry = 5)
    {
        return $this->workFlowMsg($entry)->get();
    }

    /**
     * 工作流消息
     * 说明：客服负责的账号，并且没有被别人操作的 未读或者处理中
     * @param $query
     * @param $entry
     * @return mixed
     */
    public function scopeWorkFlowMsg ($query,$entry)
    {
        $user_id = request()->user()->id;
        $account_ids = Channel_Accounts::where('customer_service_id',$user_id)->get()->pluck('id')->toArray(); //客服所属的账号

        return $query->where(function ($query) use ($account_ids){
            $query->where(['status'=> 'UNREAD', 'required'=> 1, 'dont_reply' => 0 ,'read' => 0])
                ->whereIn('account_id',$account_ids);
            })
            ->orWhere(function($query) use ($user_id, $account_ids){
                $query->where('status','=','PROCESS')
                    ->where('assign_id','=',$user_id)
                    ->where('required','=',1)
                    ->where('dont_reply','=',0)
                    ->where('read','=',0)
                ->whereIn('account_id',$account_ids);
            })
            ->take($entry)
            ->orderBy('id', 'DESC');
    }

    public function contentTemplate ()
    {
        if($this->channel_diver_name){
            switch ($this->channel_diver_name){
                case 'aliexpress':
                    break;

            }

        }
    }

    public function completeMsg(){
        $this->status = 'COMPLETE';
        if($this->save()){
            return true;
        }else{
            return false;
        }
    }


}