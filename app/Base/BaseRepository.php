<?php
/**
 * 基础仓库类
 *
 * 常规HTTP请求的CURD方法, 业务逻辑方法
 * @author  Vincent<nyewon@gmail.com>
 */
namespace App\Base;

abstract class BaseRepository
{
    //仓库调用模型
    protected $model;
    //列表展示字段
    public $columns;
    //列表过滤字段,用于查找过滤
    protected $filters;
    //验证字段,用于表单提交的数据验证
    public $rules;

    /**
     * 列表数据查找过滤
     *
     * @param  object $result 需要查找的Model对象
     * @param  object $request HTTP请求对象
     * @return object $result 查找过滤后的Model对象
     */
    public function filter($result, $request)
    {
        $result = $result->where(function ($query) use ($request) {
            foreach ($this->filters as $filterColumn) {
                if ($request->has('keywords')) {
                    $filter_operator = $request->has($filterColumn . '_operator') ? $request->input($filterColumn . '_operator') : 'like';
                    $filter_value = $filter_operator === 'like' ? '%' . trim($request->input('keywords')) . '%' : trim($request->input($filterColumn));
                    $query = $query->orWhere($filterColumn, $filter_operator, $filter_value);
                }
            }
        });

        return $result;
    }

    /**
     * 列表数据排序
     *
     * @param  object $result 需要排序的Model对象
     * @param  object $request HTTP请求对象
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
     * @param  object $request HTTP请求对象
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
     * 产品详情
     *
     * @param $id
     * @return object
     */
    public function detail($id)
    {
        return $this->model->find($id);
    }

    /**
     * 存储资源
     *
     * @param  object $request HTTP请求对象
     * @return bool
     */
    abstract public function store($request);

    /**
     * 编辑指定id资源
     *
     * @param  int $id 资源id
     * @return Illuminate\Support\Collection
     */
    public function edit($id)
    {
        return $this->detail($id);
    }

    /**
     * 更新指定id资源
     *
     * @param  int $id 资源id
     * @param  object $request HTTP请求对象
     * @return bool
     */
    abstract public function update($id, $request);

    /**
     * 删除指定id资源
     *
     * @param  int $id 资源id
     * @return bool
     */
    public function destroy($id)
    {
        return $this->model->destroy($id);
    }
}
