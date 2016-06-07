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
use App\Models\CountriesModel;
use App\Models\UserModel;
use App\Models\PaypalsModel;

class AccountController extends Controller
{
    public function __construct(AccountModel $account)
    {
        $this->model = $account;
        $this->mainIndex = route('channelAccount.index');
        $this->mainTitle = '渠道账号';
        $this->viewPath = 'channel.account.';
    }

    public function index(){
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'paypals' => PaypalsModel::orderBy('id', 'asc')->get(['id', 'paypal_email_address']),
        ];
        return view($this->viewPath . 'index', $response);
    }
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
            'warehouses' => WarehouseModel::all(),
            'users' => UserModel::orderBy('name', 'asc')->get(['id', 'name']),
            'countries' => CountriesModel::orderBy('code', 'asc')->get(['id', 'name'])
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
            'warehouses' => WarehouseModel::all(),
            'users' => UserModel::orderBy('name', 'asc')->get(['id', 'name']),
            'countries' => CountriesModel::orderBy('code', 'asc')->get(['id', 'name'])
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
        $model->updateAccount(request()->all());
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


    public function getAccountUser()
    {
        $channel_id = request()->input('channel_id');
        $account = AccountModel::where('channel_id', $channel_id)->get()->toArray();
        return $account;
    }

    public function updateApi($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }



        $model->update(request()->all());

        $paypalIds = explode(',', request()->input("paypal_ids"));
        $model->paypal()->sync($paypalIds);

        return redirect($this->mainIndex)->with('alert', $this->alert('success', $model->alias . ' 设置API成功.'));
    }
}