<?php
/**
 * 基础仓库类
 *
 * 常规CURD, 业务逻辑
 * @author  Vincent<nyewon@gmail.com>
 */
namespace App\Base;

abstract class BaseRepository
{
    //仓库调用模型
    protected $model;
    //列表过滤字段,用于查找过滤
    protected $searchFields;
    //验证字段,用于表单提交的数据验证
    protected $rules;

    /**
     * 获取验证规则
     *
     * @param $type
     * @param string $id
     * @return mixed
     */
    public function rules($type, $id = '')
    {
        $rules = $this->rules[$type];
        if ($id) {
            foreach ($rules as $column => $rule) {
                $rules[$column] = str_replace('{id}', $id, $rule);
            }
        }

        return $rules;
    }

    /**
     * 自动组装
     *
     * @return $this
     */
    public function auto()
    {
        if (request()->has('keywords')) {
            $this->search(request()->input('keywords'));
        }

        if (request()->has('sorts')) {
            $sorts = [];
            foreach (explode(',', request()->input('sorts')) as $key => $sort) {
                $sort = explode('.', $sort);
                $sorts[$key]['field'] = $sort[0];
                $sorts[$key]['direction'] = $sort[1];
            }
            $this->sort($sorts);
        }

        $this->filter();

        return $this;
    }

    /**
     * 搜索
     *
     * @param $keywords
     * @return $this
     */
    public function search($keywords)
    {
        $this->model = $this->model->where(function ($query) use ($keywords) {
            foreach ($this->searchFields as $searchField) {
                $query = $query->orWhere($searchField, 'like', '%' . trim($keywords) . '%');
            }
        });

        return $this;
    }

    /**
     * 多字段排序
     *
     * @param $sorts
     * @return $this
     */
    public function sort($sorts)
    {
        foreach ($sorts as $sort) {
            $this->model = $this->model->orderBy($sort['field'], $sort['direction']);
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
     * 取得所有记录
     *
     * @param array $fields
     * @return mixed
     */
    public function all($fields = ['*'])
    {
        return $this->model->get($fields);
    }

    /**
     * 取得分页记录
     *
     * @param string $pageSize
     * @return mixed
     */
    public function paginate($fields = ['*'], $pageSize = '')
    {
        if (!$pageSize) {
            $pageSize = request()->has('pageSize') ? request()->input('pageSize') : config('setting.pageSize');
        }

        return $this->model->paginate($pageSize, $fields);
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
