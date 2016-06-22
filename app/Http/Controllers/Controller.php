<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DataList;

abstract class Controller extends BaseController
{

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    protected $model;
    protected $viewPath;
    protected $mainIndex;
    protected $mainTitle;

    public function metas($action, $title = null)
    {
        $metas = [
            'mainIndex' => $this->mainIndex,
            'mainTitle' => $this->mainTitle,
            'title' => $title ? $title : $this->mainTitle . config('setting.titles.' . $action),
        ];
        return $metas;
    }

    public function alert($type, $content)
    {
        $response = ['type' => $type, 'content' => $content];
        return view('common.alert', $response)->render();
    }

    public function autoList($model, $list = null, $fields = ['*'], $pageSize = null)
    {
        $list = $list ? $list : $model;
        if (request()->has('keywords')) {
            $keywords = request()->input('keywords');
            $searchFields = $model->searchFields;
            $list = $list->where(function ($query) use ($keywords, $searchFields) {
                foreach ($searchFields as $searchField) {
                    $query = $query->orWhere($searchField, 'like', '%' . trim($keywords) . '%');
                }
            });
        }
        if(request()->has('mixedSearchFields')) {
            $relateds = request()->input('mixedSearchFields');
            foreach($relateds as $type => $related) {
                switch($type) {
                    case 'relatedSearchFields':
                        foreach($related as $relation_ship => $name_arr) {
                            foreach($name_arr as $k => $name) {
                                if($name) {
                                    $list = $list->whereHas($relation_ship, function($query) use ($k, $name){
                                        $query = $query->where($k, 'like', '%'.$name.'%');
                                    });
                                }     
                            }
                        }
                        break;
                    case 'filterFields':
                        foreach($related as $key => $value3) {
                            if($value3) {
                                $list = $list->where($key, 'like', '%'.$value3.'%');
                            }
                        }
                        break;
                    case 'filterSelects':
                        foreach($related as $key => $value2) {
                            if($value2) {
                                $list = $list->where($key, $value2);
                            }
                        }
                        break;
                    case 'selectRelatedSearchs':
                        foreach($related as $relation_ship => $contents) {
                            foreach($contents as $name => $single) {
                                if($single) {
                                    $list = $list->whereHas($relation_ship, function($query) use ($name, $single){
                                        $query = $query->where($name, $single);
                                    });
                                }
                            }
                        }
                        break;
                    case 'sectionSelect':
                        foreach($related as $kind => $content) {
                            if($content['begin'] && $content['end']) {
                                $list = $list->whereBetween($kind, [$content['begin'], $content['end']]);
                            }
                        }
                        break;
                }
            }
        }
        if (request()->has('filters')) {
            foreach (DataList::filtersDecode(request()->input('filters')) as $filter) {
                $list = $list->where($filter['field'], $filter['oprator'], $filter['value']);
            }
        }
        if (request()->has('sorts')) {
            foreach (DataList::sortsDecode(request()->input('sorts')) as $sort) {
                $list = $list->orderBy($sort['field'], $sort['direction']);
            }
        } else {
            $list = $list->orderBy('id', 'desc');
        }
        if (!$pageSize) {
            $pageSize = request()->has('pageSize') ? request()->input('pageSize') : config('setting.pageSize');
        }
        return $list->paginate($pageSize, $fields);
    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
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
        $this->model->create(request()->all());
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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
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
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $model->update(request()->all());
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
        $model->destroy($id);
        return redirect($this->mainIndex);
    }
}
