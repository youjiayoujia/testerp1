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
use App\Models\WarehouseModel;
use App\Models\CountryModel;
use App\Models\UserModel;

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
            'warehouses' => WarehouseModel::all(),
            'users' => UserModel::orderBy('name', 'asc')->get(['id', 'name']),
            'countries' => CountryModel::orderBy('abbreviation', 'asc')->get(['id', 'name'])
        ];
        return view($this->viewPath . 'create', $response);
    }


    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $this->model->createAccount(request()->all());
        return redirect($this->mainIndex);
    }

    public function edit($id)
    {
        $account = $this->model->find($id);
        if (!$account) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $account,
            'channels' => ChannelModel::all(),
            'users' => UserModel::orderBy('name', 'asc')->get(['id', 'name']),
            'countries' => CountryModel::orderBy('abbreviation', 'asc')->get(['id', 'name'])
        ];
        return view($this->viewPath . 'edit', $response);
    }

    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $businesserIds = request()->input("businesser_ids");
        $businesserArray = explode(',', $businesserIds);
        $model->update(request()->all());
        $model->businessers()->sync($businesserArray);
        return redirect($this->mainIndex);
    }

    public function destroy($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $model->destoryAccount();

        return redirect($this->mainIndex);
    }
}