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
     * @return $this
     */
    public function search()
    {
        if (request()->has('keywords')) {
            $this->model = $this->model->where(function ($query) {
                foreach ($this->searchFields as $searchField) {
                    $query = $query->orWhere($searchField, 'like', '%' . trim(request()->input('keywords')) . '%');
                }
            });
        }

        return $this;
    }

    /**
     * 排序
     *
     * @return $this
     */
    public function sort()
    {
        if (request()->has('sorts')) {
            $sorts = explode(',', request()->input('sorts'));
            foreach ($sorts as $sort) {
                $sort = explode('.', $sort);
                $this->model = $this->model->orderBy($sort[0], $sort['1']);
            }
        }

        return $this;
    }

    /**
     * 过滤
     *
     * @return $this
     */
    public function filter()
    {
        return $this;
    }

    /**
     * 组装
     *
     * @return $this
     */
    public function scope()
    {
        $this->search();
        $this->sort();
        $this->filter();

        return $this;
    }

    /**
     *取得所有记录
     *
     * @return mixed
     */
    public function all()
    {
        return $this->model->get();
    }

    /**
     * 取得分页记录
     *
     * @return mixed
     */
    public function paginate()
    {
        $pageSize = request()->has('pageSize') ? request()->input('pageSize') : config('setting.pageSize');

        return $this->model->paginate($pageSize);
    }

    /**
     * 获取指定ID资源
     *
     * @param $id
     * @return object
     */
    public function get($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * 存储资源
     *
     * @param $data
     * @return mixed
     */
    public function create($data)
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
