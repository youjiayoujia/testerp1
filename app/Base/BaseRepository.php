<?php
/**
 * 基础仓库类
 *
 * 进行常规HTTP请求的CURD操作
 * @author  Vincent<nyewon@gmail.com>
 */
namespace App\Base;

abstract class BaseRepository
{
    //仓库调用模型
    protected $model;
    //列表展示字段
    public $columns;
    //列表过滤字段
    protected $filters;
    //仓库验证字段
    public $rules;

    /**
     * 列表数据查询过滤
     *
     * @param  object $result 需要查询的Model对象
     * @param  object $request HTTP请求数据
     * @return object $result 查询过滤后的Model对象
     */
    public function filter($result, $request)
    {
        foreach ($this->filters as $filterColumn) {
            if ($request->has('keywords')) {
                $filter_operator = $request->has($filterColumn . '_operator') ? $request->input($filterColumn . '_operator') : 'like';
                $filter_value = $filter_operator === 'like' ? '%' . trim($request->input('keywords')) . '%' : trim($request->input($filterColumn));
                $result = $result->orWhere($filterColumn, $filter_operator, $filter_value);
            }
        }

        return $result;
    }

    /**
     * 列表数据排序
     *
     * @param  object $result 需要排序的Model对象
     * @param  object $request HTTP请求数据
     * @return object $result 排序后的Model对象
     */
    public function sort($result, $request)
    {
        if ($request->has('orderField') AND $request->has('orderDirection')) {
            $result = $result->orderBy($request->input('orderField'), $request->input('orderDirection'));
        } else {
            $result = $result->orderBy('id', 'desc');
        }

        return $result;
    }

    /**
     * 列表
     *
     * @param  object $request HTTP请求数据
     * @return Illuminate\Support\Collection
     */
    public function index($request)
    {
        $pageSize = $request->has('pageSize') ? $request->input('pageSize') : config('setting.pageSize');
        $result = $this->model;
        $result = $this->filter($result, $request);
        $result = $this->sort($result, $request);

        return $result->paginate($pageSize);
    }

    /**
     * 存储资源
     *
     * @param  object $request HTTP请求数据
     * @return bool
     */
    abstract public function store($request);

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
     * @return void
     */
    public function destroy($id)
    {
        return $this->model->destroy($id);
    }
    #********
    #* 与资源 REST 相关的接口函数 END
    #********
}
