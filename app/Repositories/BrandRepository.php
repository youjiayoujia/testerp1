<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\BrandModel as Brand;

/**
 * 范例: 品牌
 *
 * @author youjia<youjiayoujia418@gmail.com>
 */
class BrandRepository extends BaseRepository
{
    public $columns = ['id', 'name', 'country', 'created_at', 'updated_at'];
    protected $filters = ['name', 'country'];
    public $rules = [
        'brand_name' => 'required|unique:brands,name,',
        'country' => 'required',
    ];

    public function __construct(Brand $brand)
    {
        $this->model = $brand;
    }

    /**
     * 品牌存储
     *
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function store($request)
    {
        $this->model->name = $request->input('brand_name');
        $this->model->country = $request->input('country');
        //$this->model->created_at = date('Y-m-d H:i:s',time());

        return $this->model->save();
    }

    /**
     * 更新指定ID品牌
     *
     * @param int $id 品牌ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function update($id, $request)
    {
        $brand = $this->model->find($id);
        $brand->name = $request->input('brand_name');
        $brand->country = $request->input('country');
        return $brand->save();
    }

}
