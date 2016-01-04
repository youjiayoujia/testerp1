<?php
/**
 * 渠道控制器
 *
 * 2016-01-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ChannelRepository;

class ChannelController extends Controller
{
    protected $channel;

    public function __construct(Request $request, ChannelRepository $channel)
    {
        $this->request = $request;
        $this->channel = $channel;
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
            'data' => $this->channel->auto()->paginate(),
        ];

        return view('channel.index', $response);
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
            'channel' => $this->channel->get($id),
        ];

        return view('channel.show', $response);
    }

    /**
     * 新建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('channel.create');
    }

    /**
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->channel->rules('create'));

        $data = $this->request->all();
        $data['created_by'] = 1; //todo user_id
        $data['updated_by'] = 1; //todo user_id
        $this->channel->create($data);

        return redirect(route('channel.index'));
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $response = [
            'channel' => $this->channel->get($id),
        ];

        return view('channel.edit', $response);
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
        $this->validate($this->request, $this->channel->rules('update', $id));

        $data = $this->request->all();
        $data['updated_by'] = 1; //todo user_id
        $this->channel->update($id, $data);

        return redirect(route('channel.index'));
    }

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $this->channel->destroy($id);
        return redirect(route('channel.index'));
    }
}