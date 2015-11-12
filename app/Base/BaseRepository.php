<?php

namespace App\Base;

use Config;

abstract class BaseRepository
{

    protected $model;

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

    public function toJGrid()
    {

    }

    #********
    #* 与资源 REST 相关的接口函数 END
    #********
}
