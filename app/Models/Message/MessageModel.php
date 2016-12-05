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
use App\Models\Channel\AccountModel;

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

    public $searchFields = ['id'=>'ID','subject'=>'主题', 'from_name'=>'发信人', 'from'=>'发件邮箱' ,'label' => '消息类型' , 'channel_order_number' => '平台订单号'];

    public $rules = [];

    public function account()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel');
    }
    public function assigner()
    {
        return $this->belongsTo('App\Models\UserModel', 'assign_id');
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

    public function getChannelNameAttribute(){
        if(!empty($this->channel_id)){
            return $this->channel->name;
        }else{
            return '无';
        }
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

    /**
     * 工作流获取下一个message
     * 说明：只获取会话客服被分配的账号下的的信息
     * @param $userId
     * @return mixed
     */
    public function getOne($userId)
    {
        $acounts_ary = $this->getUserAccountIDs($userId);
        return $this
            ->whereIn('account_id', $acounts_ary) //用户所属账号的信息
            ->where('status', 'UNREAD')
            ->orWhere(function ($query) use ($userId) {
                $query->where('assign_id', $userId)->where('status', 'PROCESS')->where('dont_reply','<>',1);
            })->first();
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


    //根据邮件或者 from 关联订单
    public function guessRelatedOrders($email = null)
    {
        $relatedOrders = [];
        if ($this->last) {
            $relatedOrders['history'] = $this->last->relatedOrders;
        }
        $email = $email ? $email : $this->from;
        $relatedOrders['email'] = OrderModel::where('email', $email)->get();
        return $relatedOrders;
    }

    public function assignToOther($fromId, $assignId)
    {

        if ($this->assign_id == $fromId) {
            $assignUser = UserModel::find($assignId);
            if ($assignUser) {
                $this->assign_id = $assignId;
                return $this->save();
            }
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
        if ($this->assign_id == $userId) {
            $this->required = 0;
            $this->status = 'COMPLETE';
            return $this->save();
        }
        return false;
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
            $this->type_id = $data['type_id']?$data['type_id']:"";
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
    /**
     * 更多搜索
     * @return array
     */
    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [],
            'filterSelects' => [],
            'selectRelatedSearchs' => [],
            'sectionSelect' => ['time'=>['created_at']],

        ];
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
                                $product_html .= '<td><a href="'.$message_fields_ary['product_product_url'].'">链接</a></td>';
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
        if($fields_ary){
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
                    $transaction_id = $fields_ary['order_items'][0]['Order']['transaction_id'];  //wish交易号
                    $order_obj = OrderModel::where('transaction_number','=',$transaction_id)->first();
                    $order_id = empty($order_obj) ? '' : $order_obj->id;   //根据 orders 表 交易号
                    break;
                case 'aliexpress':
                    if(!empty($fields_ary['order_id'])){
                        $order_obj = OrderModel::where('channel_ordernum','=',$fields_ary['order_id'])->first();
                        $order_id = (!empty($order_obj)) ? $order_obj->id : '';
                    }else{
                        $order_id = '';
                    }
                    break;
                default:
                    $order_id = '';
            }
        }else{
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
                if($files){
                    $html .= '<p><strong>Transaction id</strong>:'.$files['order_items'][0]['Order']['transaction_id'].'</p>';
                    $html .= '<p><strong>语言</strong>:'.$files['locale'].'</p>';
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

    /**
     *
     */
/*    public function getIsAliOptionMsgAttribute(){
        if ($this->related == '0'){
            $messages = $this->where('channel_order_number', $this->channel_order_number)->get();
            dd($messages);
        }
    }*/


}