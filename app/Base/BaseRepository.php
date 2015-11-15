<?php

namespace App\Base;

abstract class BaseRepository
{

    protected $model;

    public function gridColumns()
    {
        return json_encode($this->model->gridColumns);
    }

    public function filter($request)
    {
        $result = $this->model;
        foreach ($result->filters as $filterColumn) {
            if ($request->has($filterColumn)) {
                $filter_operator = $request->input($filterColumn . '_operator');
                $filter_value = $filter_operator === 'like' ? '%' . trim($request->input($filterColumn)) . '%' : trim($request->input($filterColumn));
                $result = $result->where($filterColumn, $filter_operator, $filter_value);
            }
        }
        return $result;
    }

    #********
    #* 与资源 REST 相关的接口函数 START
    #********

    /**
     * 资源列表
     *
     * @param  array $data 必须传入与模型查询相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @param  string $pageSize 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    abstract public function index($request);

    /**
     * 存储资源
     *
     * @param  array $inputs 必须传入与存储模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model
     */
    abstract public function store($inputs, $extra);

    /**
     * 编辑特定id资源
     *
     * @param  int $id 资源id
     * @param  string|array $extra 可选额外传入的参数
     * @return Illuminate\Support\Collection
     */
    abstract public function edit($id, $extra);

    /**
     * 更新特定id资源
     *
     * @param  int $id 资源id
     * @param  array $inputs 必须传入与更新模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return void
     */
    abstract public function update($id, $inputs, $extra);

    /**
     * 删除特定id资源
     *
     * @param  int $id 资源id
     * @param  string|array $extra 可选额外传入的参数
     * @return void
     */
    abstract public function destroy($id, $extra);
    #********
    #* 与资源 REST 相关的接口函数 END
    #********
}
