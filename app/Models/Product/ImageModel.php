<?php

namespace App\Models\product;

use App\Base\BaseModel;

class ImageModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'user_id', 'type','image_path','image_name'];
	
	/**
	*验证图片是否已经上传
	*/
	public function isImageUpload($data){
		 $result=$this->whereRaw("product_id=? and type='?'",[$data['product_id'],$data['type']])->get();
		 $resNum=count($result);
		 if($resNum>0){
			return $result[0]->id;
			}else{
			return false; 
				 }
		}

}
