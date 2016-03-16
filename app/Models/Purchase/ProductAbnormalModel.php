<?php
/**
 * 产品异常model
 *
 * User: tup
 * Date: 16/1/4
 * Time: 下午5:02
 */
 
namespace App\Models\Purchase;

use App\Base\BaseModel;
use App\Models\Product\ImageModel;
use App\Models\Product\SupplierModel;
use App\Models\ProductModel;

class ProductAbnormalModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_abnormals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sku_id', 'user_id', 'status','update_userId','remark','arrival_time','type','image_id'];
	
	protected $searchFields = ['id', 'sku_id', 'user_id', 'type','status','update_userId'];
    public $rules = [
        'create' => [
            'sku_id' => 'required',
            'user_id' => 'required',
        ],
        'update' => [
            'sku_id' => 'required',
            'user_id' => 'required',
        ]
    ];
	
	
	public function image()
    {
        return $this->belongsTo('App\Models\Product\ImageModel', 'image_id');
    }

	/**
     * 更新异常
     *
     * @param $data 
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function updateProductAbnormal($id,$data)
    { 	
		$productAbnormal=$this->find($id);
		return $productAbnormal->update($data);	
    } 		
	
}
