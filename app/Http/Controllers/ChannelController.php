<?php
/**
 * 渠道控制器
 *
 * 2016-01-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers;

use App\Models\ChannelModel;

class ChannelController extends Controller
{
    public function __construct(ChannelModel $channel)
    {
        $this->model = $channel;
        $this->mainIndex = route('channel.index');
        $this->mainTitle = '渠道';
        $this->viewPath = 'channel.';
    }
}