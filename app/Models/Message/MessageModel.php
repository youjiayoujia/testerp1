<?php
/**
 * User: jiangdi
 * Date: 2016/6/20
 * Time: 19:04
 */
namespace App\Models\Message;
use App\Base\BaseModel;
use App\Models\Order\PackageModel;
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


}