<?php
/**
 * 渠道账号控制器
 *
 * 2016-01-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Channel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ChannelRepository;
use App\Repositories\Channel\AccountRepository;

class AccountController extends Controller
{
    protected $account;

    public function __construct(Request $request, AccountRepository $account)
    {
        $this->request = $request;
        $this->account = $account;
        $this->mainIndex = route('channelAccount.index');
        $this->mainTitle = '渠道账号';
    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->request->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->account->auto()->paginate(),
        ];

        return view('channel.account.index', $response);
    }

    /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'account' => $this->account->get($id),
        ];

        return view('channel.account.show', $response);
    }

    /**
     * 新建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ChannelRepository $channel)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => $channel->all(),
        ];

        return view('channel.account.create', $response);
    }

    /**
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->account->rules('create'));
        $this->account->create($this->request->all());

        return redirect($this->mainIndex);
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, ChannelRepository $channel)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => $channel->all(),
            'account' => $this->account->get($id),
        ];

        return view('channel.account.edit', $response);
    }

    /**
     * 更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->account->rules('update', $id));
        $this->account->update($id, $this->request->all());

        return redirect($this->mainIndex);
    }

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $this->account->destroy($id);
        return redirect($this->mainIndex);
    }
}