<?php
/**
 * 汇率控制器
 * 处理汇率相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event\ChildModel;

class EventChildController extends Controller
{
    public function __construct(ChildModel $child)
    {
        $this->model = $child;
        $this->mainIndex = route('eventChild.index');
        $this->mainTitle = '事件记录';
        $this->viewPath = 'event.child.';
    }
}