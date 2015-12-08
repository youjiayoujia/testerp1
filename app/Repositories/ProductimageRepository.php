<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\ProductModel as Product;
use App\Models\Product_imageModel as Product_image;

/**
 * 范例: 产品库
 *
 * @author Vincent<nyewon@gmail.com>
 */
class ProductimageRepository extends BaseRepository
{
    public $columns = ['id','product_id','suppliers_url','type'];
    protected $filters = ['product_id'];
    public $rules = [
        'product_id' => 'required|unique:product_id,suppliers_url,type,image_path'
    ];
	 

    public function __construct(Product_image $Product_image)
    {
        $this->model = $Product_image;
    }

    /**
     * 产品存储
     *
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function store($request)
    {
        $this->model->product_id = $request->input('product_id');
        $this->model->user_id = $request->input('user_id');
        $this->model->type = $request->input('type');
		$this->model->type = $request->input('image_path');
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
        $this->model->product_id = $request->input('product_id');
        $this->model->user_id = $request->input('user_id');
        $this->model->type = $request->input('type');
		$this->model->type = $request->input('image_path');
        return $product->save();
    }

    /**
     * 获取产品品牌
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function Product()
    {
        return Product::all();
    }

}
