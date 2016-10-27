<?php
/**
 * 物流分区控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/10/23
 * Time: 上午12:49
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\CountriesModel;
use App\Models\Logistics\PartitionModel;
use App\Models\Logistics\PartitionSortModel;
use App\Models\UserModel;

class PartitionController extends Controller
{
    public function __construct(PartitionModel $partition)
    {
        $this->model = $partition;
        $this->mainIndex = route('logisticsPartition.index');
        $this->mainTitle = '物流分区';
        $this->viewPath = 'logistics.partition.';
    }

    /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'partitionSorts' => $model->partitionSorts,
        ];
        return view($this->viewPath . 'show', $response);
    }

    /**
     * 新建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'countries' => CountriesModel::all(),
        ];

        return view($this->viewPath . 'create', $response);
    }

    /**
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $model = $this->model->create(request()->all());
        foreach(request('country_id') as $value) {
            $data['country_id'] = $value;
            $data['logistics_partition_id'] = $model->id;
            PartitionSortModel::create($data);
        }
        $model = $this->model->with('partitionSorts')->find($model->id);
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', base64_encode(serialize($model)));

        return redirect($this->mainIndex);
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $arr = [];
        foreach ($model->partitionSorts as $partitionSort) {
            $arr[] = $partitionSort->country_id;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'countries' => CountriesModel::all(),
            'arr' => $arr,
        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        $userName = UserModel::find(request()->user()->id);
        $from = base64_encode(serialize($model));
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $model->update(['name' => request('name')]);
        $partitionSorts = $model->partitionSorts;
        foreach($partitionSorts as $partitionSort) {
            $partitionSort->delete();
        }
        foreach(request('country_id') as $value) {
            $data['country_id'] = $value;
            $data['logistics_partition_id'] = $id;
            PartitionSortModel::create($data);
        }
        $model = $this->model->with('partitionSorts')->find($id);
        $to = base64_encode(serialize($model));
        $this->eventLog($userName->name, '数据更新,id='.$id, $to, $from);

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
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $partitionSorts = $model->partitionSorts;
        foreach($partitionSorts as $partitionSort)
        {
            $partitionSort->delete();
        }
        $model->destroy($id);
        return redirect($this->mainIndex);
    }
}