<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\BrandModel as Brand;
use App\Models\ProductModel as Product;

/**
 * ProductRepository
 *
 * @author Vincent<nyewon@gmail.com>
 */
class ProductRepository extends BaseRepository
{
    protected $columns = [
        ['name' => 'id', 'label' => '#'],
        ['name' => 'brand_id', 'label' => '品牌'],
        ['name' => 'size', 'label' => '型号'],
        ['name' => 'color', 'label' => '颜色'],
        ['name' => 'created_at', 'label' => '创建日期']
    ];

    protected $filters = ['size', 'color'];

    public $rules = [
        'size' => 'required|unique:products,size',
        'color' => 'required',
    ];

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function store($request)
    {
        $this->model->brand_id = $request->input('brand_id');
        $this->model->size = $request->input('size');
        $this->model->color = $request->input('color');

        return $this->model->save();
    }

    public function edit($id, $extra)
    {
        return;
    }

    public function update($id, $inputs, $extra)
    {
        return;
    }

    public function destroy($id, $extra)
    {
        return;
    }

    public function getAllBrands()
    {
        return Brand::all();
    }

}
