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

    public function store($request)
    {
        $this->model->brand_id = $request->input('brand_id');
        $this->model->size = $request->input('size');
        $this->model->color = $request->input('color');

        return $this->model->save();
    }

    public function edit($id)
    {
        return $this->model->find($id);
    }

    public function update($id, $request)
    {
        $product = $this->model->find($id);
        $product->brand_id = $request->input('brand_id');
        $product->size = $request->input('size');
        $product->color = $request->input('color');

        return $product->save();
    }

    public function getBrands()
    {
        return Brand::all();
    }

}
