<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\BrandModel as Brand;
use App\Models\ProductModel as Product;
use App\Models\Product_imageModel as Product_image;

/**
 * 范例: 产品库
 *
 * @author Vincent<nyewon@gmail.com>
 */
class ProductRepository extends BaseRepository
{
    public $columns = ['id', 'brand', 'size', 'color', 'created_at'];
    protected $filters = ['size', 'color'/*,'product_id','type'*/];
    public $rules = [
        'size' => 'required|unique:products,size',
        'color' => 'required',
		/*'product_id'=>'required|unique:product_images,product_id',		 
		'type'=>'required|unique:product_images,type'*/
    ];
	 

    public function __construct(Product $product,Product_image $product_image)
    {
        $this->model = $product;
		$this->pmodel= $product_image;
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
	
	
	/**
     * 上传产品图片
     *
     * @param int $id 产品ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function store_image($image_path,$product_id,$type)
    {	$this->pmodel=new Product_image;
        $this->pmodel->type = $type;
        $this->pmodel->product_id = $product_id;
        $this->pmodel->user_id = 1;
        $this->pmodel->image_path = $image_path;
        return $this->pmodel->save();
    }
	    /**
     * 更新产品图片
     *
     * @param int $id 产品ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function update_image($id, $image_path)
    {	
        $product_images=$this->pmodel->find($id);
        $product_images->user_id = 1;
        $product_images->image_path = $image_path;
        return $product_images->save();
    }


	 /**
     * 获取图片
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getImage($product_id,$type)
    {
        return Product_image::whereRaw('product_id=? and type=?',[$product_id,$type])->get();
    }

}
