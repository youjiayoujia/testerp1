<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\BrandModel as Brand;
use App\Models\Product;
use App\Models\Product_imageModel as Product_image;
use App\Repositories\Helpers;
/**
 * 范例: 产品库
 *
 * @author Vincent<nyewon@gmail.com>
 */
class ProductRepository extends BaseRepository
{
	protected $searchFields = ['id','name', 'c_name', 'created_at'];
    public $rules = [
        'create' => ['name' => 'required|unique:products,name','c_name' => 'required',],
        'update' => []
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
    public function store_image($path,$product_id,$type)
    {	$this->pmodel=new Product_image;
        $this->pmodel->type = $type;
        $this->pmodel->product_id = $product_id;
        $this->pmodel->user_id = 1;
        $this->pmodel->path = $path;
        return $this->pmodel->save();
    }
	    /**
     * 更新产品图片
     *
     * @param int $id 产品ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function update_image($id, $path)
    {	
        $product_images=$this->pmodel->find($id);
        $product_images->user_id = 1;
        $product_images->path = $path;
        return $product_images->save();
    }
 
	/**
     * 获取默认图片
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getImageTypes($product_id)
    {
		 
        return Product_image::whereRaw('product_id='.$product_id)->get();
    }
	 /**
     * 查询图片是否已上传
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getImage($product_id,$type)
    {
        return Product_image::whereRaw('product_id=? and type=?',[$product_id,$type])->get();
    }

	/**
	 * 上传图片
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function imageUpdate($request){
		$product_id=$request->id;
		$type=$request->type; 
		$result=$this->getImage($product_id,$type);
		$count=count($result); 
		 if($count==0){
			$product_image_id=0;
		 }else{
			$product_image_id=$result[0]->id;
			 }
		 $path='storage/uploads/product/'.$product_id.'/'.$type.'/';
		 if(!is_dir($path)){	
		 	mkdir(iconv("UTF-8", "GBK", $path),0777,true);
		 } 	 
		 if(is_dir($path)){
			 if($type=='default'){
			 $this->defaultImageUpload($request,$product_id,$type,$product_image_id,$path);
		 }else{	 
			 $this->UploadImage($request,$product_id,$type,$product_image_id,$path);
		 }
		 }else{
			echo '文件夹创建失败！';exit; 
		 } 
		}
	/**
	*处理默认图片上传
	*
	*
	*/
	public function defaultImageUpload($request,$product_id,$type,$product_image_id,$path){
		$path='';
		 $file=$request->file('map0');
		 if($request->hasFile('map0')){
				$suffix = $file -> getClientOriginalExtension();	
				if($suffix == 'jpg' || $suffix == 'jpeg' || $suffix == 'png' || $suffix == 'gif'){					
				$nownanme=$product_id.$type.'.'.$suffix;
					$file->move($path,$nownanme);			 
					}
					$src_img =$path.$nownanme;
					$dst_img = $path.$product_id.$type.'s.'.$suffix;
					$path=$src_img.'#'.$dst_img;
					Image::make($src_img,array('width' => 200,'height' => 300,))->save($dst_img); 
						if($product_image_id>0){
							$this->update_image($product_image_id,$path);	
							}else{
							$this->store_image($path,$product_id,$type);	
							}   
		 }
		
		}	
	/**
	*处理多平台图片上传
	*
	*
	*/
	public function UploadImage($request,$product_id,$type,$product_image_id,$path){
		$path='';
		 for($i=0;$i<6;$i++){
			$file = $request->file('map'.$i);								
			if($request->hasFile('map'.$i)){
				$suffix=$file -> getClientOriginalExtension();
					if($suffix == 'jpg' || $suffix == 'jpeg' || $suffix == 'png' || $suffix == 'gif'){					
						$nownanme=$product_id.$type.$i.'.'.$suffix;
						$file->move($path,$nownanme);			 
						if($i==0){
							$path=$path.$nownanme;
						 }else{ 
							$path=$path.$nownanme.'#'.$path;
						}			
					}else{
						echo '请上传正确的图片格式！';exit;
					}
			}else{
					echo '上传出错！';exit;
			}
		}	 
		if($product_image_id>0){ 
		$this->update_image($product_image_id,$path);	
		}else{	
		$this->store_image($path,$product_id,$type);	
		} 
	}
		
		
	/**
	 * 上传压缩包
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function zipUpdate($request){
		$product_id=$request->id; 
		$helper= new Helpers;
		$type=$request->type;
		$file = $request->file('zip');
			$zipPath='storage/uploads/zip/';								
			if($request->hasFile('zip')){
				$clientName = $file -> getClientOriginalName();
				$suffix=substr(strrchr($clientName, '.'), 1);			 
				 if($clientName!=$product_id.'.'.$suffix){
					 echo '请上传正确的文件压缩包！';exit;
					 }
				if($suffix == 'zip' || $suffix == 'rar' || $suffix == '7z' || $suffix == 'cab'){					 			
				$nownanme=$product_id.$type.'.'.$suffix; 
				$file->move($zipPath,$nownanme);
				$zippath='storage/uploads/zip/'.$nownanme;	
				$path='storage/uploads/product/';
				$res=$helper->decompression($zippath,$path);
				  $dir_path=$path.$product_id.'/';				 
				  $dir_name=$helper->get_dirname($dir_path);
				  foreach($dir_name as $key=>$value){
						  $dirPathType='storage/uploads/product/'.$product_id.'/'.$value.'/';
						  $paths[$value]=$helper->get_dirname($dirPathType);	 
					  }
				 foreach($paths as $key=>$val){
						 $type=$key;
						 foreach($val as $k=>$v){
						 $orname=$path.$product_id.'/'.$key.'/'.$v;
						 $now_path='storage/uploads/product/'.$product_id.'/'.$key.'/';
						 if($key=='default'){
							  $suffixa=substr(strrchr($v, '.'), 1);
							  $now_name=$product_id.'default.'.$suffixa;
							  rename($orname,$now_path.$now_name);
								$src_img =$now_path.$now_name;
								$dst_img = $now_path.$product_id.'defaults.'.$suffixa;
								$path=$src_img.'#'.$dst_img;
								Image::make($src_img,array('width' => 200,'height' => 300,))->save($dst_img); 
							 }else{ 
								 $suffixa=substr(strrchr($v, '.'), 1);
								 $now_name=$product_id.$key.$k.'.'.$suffixa;
								 rename($orname,$now_path.$now_name);
									 if($k>0){
									 	$path=$now_path.$now_name.'#'.$path;
									 }else{
									 	$path=$now_path.$now_name;
									 }
								 }
						 }
						 $result=$this->getImage($product_id,$type);
						 $count=count($result); 
		 				 if($count==0){
							$product_image_id=0;
						 }else{
							$product_image_id=$result[0]->id;
						 }	 
						 if($product_image_id>0){
							$this->update_image($product_image_id,$path);	
							}else{
							$this->store_image($path,$product_id,$type);	
						} 
				 
				 }
				}
				
				}
		}
	/**
	 * 压缩包批量上传产品图片
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */		
	public function zipsUpload($request){
		$helper= new Helpers;
		$path='storage/uploads/zip/';
		$file = $request->file('zip');
		if($request->hasFile('zip')){
			$clientName = $file -> getClientOriginalName();
			$suffix=substr(strrchr($clientName, '.'), 1);
			if($suffix == 'zip' || $suffix == 'rar' || $suffix == '7z' || $suffix == 'cab'){	
			$file->move($path,$clientName);
			$zippath='storage/uploads/zip/'.$clientName;	
			$path='storage/uploads/';
			$res=$helper->decompression($zippath,$path);
			$product_ids=$helper->get_dirname($path.'product/');
			foreach($product_ids as $key=>$value){
				$product_imageTypes=$helper->get_dirname($path.'product/'.$value.'/');	 
				foreach($product_imageTypes as $key=>$val){
					$type=$val;
					$product_paths=$helper->get_dirname($path.'product/'.$value.'/'.$val.'/');
					$result=$this->getImage($value,$val);
					$count=count($result); 
		 			if($count==0){
						$product_image_id=0;
					}else{
						$product_image_id=$result[0]->id;
					}
						foreach($product_paths as $num=>$v){							
						if($val=='default'){
							$product_id=$value;		 
							$suffixa=substr(strrchr($v, '.'), 1);
							$src_img=$path.'product/'.$value.'/'.$val.'/'.$v;
							$dst_img=$path.'product/'.$value.'/'.$val.'/'.$value.'defaults.'.$suffixa;
							$helper->img2thumb($src_img, $dst_img,$width = 200, $height = 100, $cut = 0, $proportion = 0);
							$path=$src_img.'#'.$dst_img;
						}else{
							$suffixa=substr(strrchr($v, '.'), 1);
							$now_name=$value.$val.$num.'.'.$suffixa;
							rename($path.'product/'.$value.'/'.$val.'/'.$v,$path.'product/'.$value.'/'.$val.'/'.$now_name);
							if($num>0){
								$path=$path.'product/'.$value.'/'.$val.'/'.$now_name.'#'.$path ;
							}else{
								$path=$path.'product/'.$value.'/'.$val.'/'.$now_name;
							}
						} 
					  }
					if($product_image_id>0){
						$this->update_image($product_image_id,$path);	
					}else{
						 $this->store_image($path,$value,$type);	
					} 
				}
			}//var_dump($product_imageTypes);exit;					 
		}			
	 }
	}
		
}
