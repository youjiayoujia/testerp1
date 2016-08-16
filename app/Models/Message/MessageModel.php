<?php
/**
 * User: jiangdi
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

    public $searchFields = ['id'=>'ID','subject'=>'主题', 'from_name'=>'发信人', 'from'=>'发件邮箱'];

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
        if ($this->replies()->create($data)) {
            //记录回复邮件类型
            $this->type_id = $data['type_id']?$data['type_id']:"";
            $this->status = 'COMPLETE';
            $this->end_at = date('Y-m-d H:i:s', time());
            return $this->save();
        }
        return false;
    }
    public function getAttachment()
    {
        return $this->hasMany('App\Models\Message\MessageAttachment', 'message_id');
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
                                   $html .= '<div class="alert alert-warning col-md-10" role="alert"><p><strong>用户名：</strong>'.$this->from_name.':</p>'.$item['Reply']['message'];
                                   $html .= '<p class="time"><strong>时间：</strong>'.$item['Reply']['date'].'</p>';
                                   $html .= '<div class="alert-danger"><strong>Wish翻译: </strong><p>'.$item['Reply']['translated_message'].'</p><p>'.$item['Reply']['translated_message_zh'].'</p></div>';
                                   $html .= '</div>';
                               }else{
                                   $html .= '<div class="alert alert-success col-md-10" role="alert" style="float: right"><p><strong>用户名：</strong>'.$item['Reply']['sender'].':</p>'.$item['Reply']['message'];
                                   $html .= '<p class="time"><strong>时间：</strong>'.$item['Reply']['date'].'</p>';
                                   $html .= '</div>';
                               }
                           }
                        }
                        break;
                    case 'aliexpress':
                        //dd($content->result);exit;
                        $message_content = array_reverse($content->result); //逆序
                        foreach ($message_content as $k => $item){
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

                                $html .= '<div class="alert alert-warning col-md-10" role="alert"><p><strong>Sender: </strong>'.$item->senderName.':</p><strong>Content: </strong>'.$content;
                                $html .= '<p class="time"><strong>Time: </strong>'.$datetime.'</p>';
                                $html .= '<div class="" style="display: none;"><strong>翻译结果: </strong><p class="content"></p></div>';
                                $html .= '<button style="float: right;" type="button" class="btn btn-success btn-translation" need-translation-content="'.$content.'">
                                    翻译
                                </button>';
                                $html .= '</div>';
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
                        $html = 'invalid message type';
                }
            }

            return $html;
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
        $order_id = $this->getChannelMessageOrderId();
        $order_obj = OrderModel::where('channel_ordernum','=',$order_id)->first();
        if($order_obj){
            if($this->relatedOrders()->create(['order_id' => $order_obj->id])){
                $this->related = 1;
                $this->save();
            }

        }
    }

    public function getChannelMessageOrderId(){
        $fields_ary = $this->MessageFieldsDecodeBase64;
        if($fields_ary){
            switch ($this->getChannelDiver()){
                case 'ebay':
                    $order_id = $fields_ary['ItemID'];
                    break;
                case 'wish':
                    $transaction_id = $fields_ary['order_items'][0]['Order']['transaction_id'];  //wish交易号
                    $order_obj = OrderModel::where('channel_ordernum','=',$transaction_id)->first();
                    $order_id = empty($order_obj) ? '' : $order_obj->id;   //根据 orders 表 交易号
                    break;
                case 'aliexpress':
                    $order_id = $fields_ary['order_id'];
                    break;
                default:
                    $order_id = '';

            }
        }else{
            $order_id = '';
        }
        return $order_id;
    }

}