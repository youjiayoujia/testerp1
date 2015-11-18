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
        ['field' => 'id', 'title' => '#'],
        ['field' => 'brand_id', 'title' => '品牌'],
        ['field' => 'size', 'title' => '型号'],
        ['field' => 'color', 'title' => '颜色'],
        ['field' => 'created_at', 'title' => '创建日期']
    ];

    protected $filters = ['size', 'color',];

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function store($inputs, $extra)
    {
        return;
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
