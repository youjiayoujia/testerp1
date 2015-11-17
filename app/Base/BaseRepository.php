<?php
/**
 * 基础仓库类
 *
 * 进行常规HTTP请求的CURD操作
 * @author  Vincent<nyewon@gmail.com>
 */
namespace App\Base;

use Config;

abstract class BaseRepository
{
    //仓库调用模型
    protected $model;
    //仓库Grid展示字段
    protected $columns;
    //仓库Grid过滤字段
    protected $filters;

    /**
     * 列表展现字段
     *
     * @return Array
     */
    public function columns()
    {
        return $this->columns;
    }

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
            if ($request->has($filterColumn)) {
                $filter_operator = $request->has($filterColumn . '_operator') ? $request->input($filterColumn . '_operator') : 'li';
                $filter_value = $filter_operator === 'like' ? '%' . trim($request->input($filterColumn)) . '%' : trim($request->input($filterColumn));
                $result = $result->where($filterColumn, $filter_operator, $filter_value);
            }
        }

        return $result;
    }

    /**
     * 列表数据排序
     *
     * @param  object $result 需要查询的Model对象
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
        $pageSize = $request->has('pageSize') ? $request->input('pageSize') : Config::get('setting.pageSize');
        $result = $this->model;
        $result = $this->filter($result, $request);
        $result = $this->sort($result, $request);

        return $result->paginate($pageSize);
    }

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
