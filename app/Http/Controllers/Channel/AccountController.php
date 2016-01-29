<?php
/**
 * 渠道账号控制器
 *
 * 2016-01-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Channel;

use App\Http\Controllers\Controller;
use App\Models\ChannelModel;
use App\Models\Channel\AccountModel;

class AccountController extends Controller
{
    public function __construct(AccountModel $account)
    {
        $this->model = $account;
        $this->mainIndex = route('channelAccount.index');
        $this->mainTitle = '渠道账号';
        $this->viewPath = 'channel.account.';
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
        ];
        return view($this->viewPath . 'create', $response);
    }

    public function edit($id)
    {
        $account = $this->model->find($id);
        if (!$account) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
            'model' => $account,
        ];
        return view($this->viewPath . 'edit', $response);
    }
}