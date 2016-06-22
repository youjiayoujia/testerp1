<?php
/**
 * User: jiangdi
 * Date: 2016/6/20
 * Time: 19:04
 */
namespace App\Models\Message;
use App\Base\BaseModel;
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

}