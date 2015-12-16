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
    public $model;
    //列表过滤字段,用于查找过滤
    protected $searchFields;
    //验证字段,用于表单提交的数据验证
    public $rules;

    /**
     * 搜索
     *
     * @param $result
     * @return mixed
     */
    public function search($result)
    {
        if (request()->has('keywords')) {
            $result = $result->where(function ($query) {
                foreach ($this->searchFields as $searchField) {
                    $query = $query->orWhere($searchField, 'like', '%' . trim(request()->input('keywords')) . '%');
                }
            });
        }

        return $result;
    }

    /**
     * 排序
     *
     * @param $result
     * @return mixed
     */
    public function sort($result)
    {
        if (request()->has('sorts')) {
            $sorts = explode(',', request()->input('sorts'));
            foreach ($sorts as $sort) {
                $sort = explode('.', $sort);
                $result = $result->orderBy($sort[0], $sort['1']);
            }
        }

        return $result;
    }

    /**
     * 组装
     *
     * @return mixed
     */
    public function scope()
    {
        $result = $this->model;
        $result = $this->search($result);
        $result = $this->sort($result);

        return $result;
    }

    /**
     *取得所有记录
     *
     * @return mixed
     */
    public function all()
    {
        return $this->scope()->get();
    }

    /**
     * 取得分页记录
     *
     * @return mixed
     */
    public function paginate()
    {
        $pageSize = request()->has('pageSize') ? request()->input('pageSize') : config('setting.pageSize');
        return $this->scope()->paginate($pageSize);
    }

    /**
     * 获取指定ID资源
     *
     * @param $id
     * @return object
     */
    public function get($id)
    {
        return $this->model->find($id);
    }

    /**
     * 存储资源
     *
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        return $this->model->create($data);
    }

    /**
     * 更新指定id资源
     *
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        return $this->get($id)->update($data);
    }

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
