<?php
/**
 * 回复队列控制器
 *
 * 2016-02-01
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Models\Message\ReplyModel;

class ReplyController extends Controller
{
    public function __construct(ReplyModel $reply)
    {
        $this->model = $reply;
        $this->mainIndex = route('messageReply.index');
        $this->mainTitle = '回复队列';
        $this->viewPath = 'message.reply.';



/*        if (!in_array(request()->user()->group, ['leader', 'super'])) {
            exit($this->alert('danger', '无权限'));
        }*/
    }

}