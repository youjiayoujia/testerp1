<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\BrandModel as Brand;
use App\Models\ProductModel as Product;

/**
 * 范例: 产品库
 *
 * @author Vincent<nyewon@gmail.com>
 */
class ProductRepository extends BaseRepository
{
    public $columns = ['id', 'brand', 'size', 'color', 'created_at'];
    protected $filters = ['size', 'color'];
    public $rules = [
        'size' => 'required|unique:products,size',
        'color' => 'required',
    ];

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    /**
     * 产品存储
     *
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function store($request)
    {
        $this->model->brand_id = $request->input('brand_id');
        $this->model->size = $request->input('size');
        $this->model->color = $request->input('color');

        return $this->model->save();
    }

    /**
     * 更新指定ID产品
     *
     * @param int $id 产品ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function update($id, $request)
    {
        $product = $this->model->find($id);
        $product->brand_id = $request->input('brand_id');
        $product->size = $request->input('size');
        $product->color = $request->input('color');

        return $product->save();
    }

    /**
     * 获取产品品牌
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getBrands()
    {
        return Brand::all();
    }

}
