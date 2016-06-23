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
    ];

    public $searchFields = ['id','subject', 'from_name', 'from', 'to'];

    public $rules = [];

    public function account()
    {
        return $this->belongsTo('App\Models\Message\AccountModel');
    }
    public function assigner()
    {
        return $this->belongsTo('App\Models\UserModel', 'assign_id');
    }
    public function test100(){
        return $this->belongsTo('App\Models\UserModel','assign_id');
    }

    public function getLabelTextAttribute()
    {
        switch ($this->label) {
            case 'INBOX':
                $result = "<span class='label label-success'>INBOX</span>";
                break;
            case 'SPAM':
                $result = "<span class='label label-warning'>SPAM</span>";
                break;
            case 'TRASH':
                $result = "<span class='label label-danger'>TRASH</span>";
                break;
            default:
                $result = "<span class='label label-info'>$this->label</span>";
                break;
        }
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

    public function getOne($userId)
    {
        return $this
            ->where('label', 'INBOX')
            ->where('status', 'UNREAD')
            ->orWhere(function ($query) use ($userId) {
                $query->where('assign_id', $userId)->where('status', 'PROCESS')->where('dont_reply','<>',1);
            })->first();
    }

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

                    //var_dump($package);exit;
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

}