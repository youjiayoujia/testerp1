<?php

namespace App\Repositories\Product;

use App\Base\BaseRepository;
use App\Models\product\Product_imageModel as Product_image;
use App\helps\Helpers;
use Chumper\Zipper\Zipper;
/**
 * 范例: 产品库
 *
 * @author Vincent<nyewon@gmail.com>
 */
class ImageRepository extends BaseRepository
{
	protected $searchFields = ['id','product_id', 'user_id', 'type', 'image_path'];
    public $rules = [
        'create' => [
					'product_id' => 'required|unique:product_images,product_id',
					'type' => 'required',
		],
        'update' => [
					'product_id' => 'required|unique:products,product_id',
					'type' => 'required',
		]
    ];
	 

    public function __construct(Product_image $product_image)
    {
		$this->model= $product_image;
    }

   
  
	
	
	/**
     * 上传产品图片
     *
     * @param int $id 产品ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function store($image_path,$product_id,$type)
    {	$this->model=new Product_image;
        $this->model->type = $type;
        $this->model->product_id = $product_id;
        $this->model->user_id = 1;
        $this->model->image_path = $image_path;
        return $this->model->save();
    }
	    /**
     * 更新产品图片
     *
     * @param int $id 产品ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function update($id, $image_path)
    {	
        $product_images=$this->model->find($id);
        $product_images->user_id = 1;
        $product_images->image_path = $image_path;
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
		echo $product_id;
		$type=$request->type; 
		$result=$this->getImage($product_id,$type);
		$count=count($result); 
		 if($count==0){
			$productImageId=0;
		 }else{
			$productImageId=$result[0]->id;
			 }
		 $path='storage/uploads/product/'.$product_id.'/'.$type.'/';
		 if(!is_dir($path)){	
		 	mkdir(iconv("UTF-8", "GBK", $path),0777,true);
		 } 	 
		 if(is_dir($path)){
			 if($type=='default'){
			 $this->defaultImageUpload($request,$product_id,$type,$productImageId,$path);
		 }else{	 
			 $this->UploadImage($request,$product_id,$type,$productImageId,$path);
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
	public function defaultImageUpload($request,$product_id,$type,$productImageId,$path){
		$image_path='';
		 $file=$request->file('map0');
		 if($request->hasFile('map0')){
				$suffix = $file -> getClientOriginalExtension();	
				if($suffix == 'jpg' || $suffix == 'jpeg' || $suffix == 'png' || $suffix == 'gif'){					
				$nownanme=$product_id.$type.'.'.$suffix;
					$file->move($path,$nownanme);			 
					}
					$src_img =$path.$nownanme;
					$dst_img = $path.$product_id.$type.'s.'.$suffix;
					$image_path=$src_img.'#'.$dst_img;
					$helper= new Helpers;
					$stat = $helper->img2thumb($src_img, $dst_img, $width = 200, $height = 300, $cut = 0, $proportion = 0);
					if($stat){
						echo 'Resize Image Success!<br/>';
						if($productImageId>0){
							$this->update($productImageId,$image_path);	
							}else{
							$this->store($image_path,$product_id,$type);	
							}   
					}else{
						echo 'Resize Image Fail!';  exit;
					}

		 }
		
		}	
	/**
	*处理多平台图片上传
	*
	*
	*/
	public function UploadImage($request,$product_id,$type,$productImageId,$path){
		$image_path='';
		 for($i=0;$i<6;$i++){
			$file = $request->file('map'.$i);								
			if($request->hasFile('map'.$i)){
				$suffix=$file -> getClientOriginalExtension();
					if($suffix == 'jpg' || $suffix == 'jpeg' || $suffix == 'png' || $suffix == 'gif'){					
						$nownanme=$product_id.$type.$i.'.'.$suffix;
						$file->move($path,$nownanme);			 
						if($i==0){
							$image_path=$path.$nownanme;
						 }else{ 
							$image_path=$path.$nownanme.'#'.$image_path;
						}			
					}else{
						echo '请上传正确的图片格式！';exit;
					}
			}else{
					echo '上传出错！';exit;
			}
		}	 
		if($productImageId>0){ 
		$this->update($productImageId,$image_path);	
		}else{	
		$this->store($image_path,$product_id,$type);	
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
			$zip_path='storage/uploads/zip/';								
			if($request->hasFile('zip')){
				$clientName = $file -> getClientOriginalName();
				$suffix=substr(strrchr($clientName, '.'), 1);			 
				 if($clientName!=$product_id.'.'.$suffix){
					 echo '请上传正确的文件压缩包！';exit;
					 }
				if($suffix == 'zip' || $suffix == 'rar' || $suffix == '7z' || $suffix == 'cab'){					 			
				$nownanme=$product_id.$type.'.'.$suffix; 
				$file->move($zip_path,$nownanme);
				$zippath='storage/uploads/zip/'.$nownanme;	
				$path='storage/uploads/product/';
				$zipper = new Zipper;
				$zipper->make($zippath)->extractTo($path); 
				  $dir_path=$path.$product_id.'/';				 
				  $dir_name=$helper->get_dirname($dir_path);
				  foreach($dir_name as $key=>$value){
						  $dir_path_type='storage/uploads/product/'.$product_id.'/'.$value.'/';
						  $image_paths[$value]=$helper->get_dirname($dir_path_type);	 
					  }
				 foreach($image_paths as $key=>$val){
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
								$image_path=$src_img.'#'.$dst_img;
								$stat = $helper->img2thumb($src_img, $dst_img, $width = 200, $height = 300, $cut = 0, $proportion = 0);
							 }else{ 
								 $suffixa=substr(strrchr($v, '.'), 1);
								 $now_name=$product_id.$key.$k.'.'.$suffixa;
								 rename($orname,$now_path.$now_name);
									 if($k>0){
									 	$image_path=$now_path.$now_name.'#'.$image_path;
									 }else{
									 	$image_path=$now_path.$now_name;
									 }
								 }
						 }
						 $result=$this->getImage($product_id,$type);
						 $count=count($result); 
		 				 if($count==0){
							$productImageId=0;
						 }else{
							$productImageId=$result[0]->id;
						 }	 
						 if($productImageId>0){
							$this->update($productImageId,$image_path);	
							}else{
							$this->store($image_path,$product_id,$type);	
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
			$zipper = new Zipper;
			$zipper->make($zippath)->extractTo($path); 
			$product_ids=$helper->get_dirname($path.'product/');
			foreach($product_ids as $key=>$value){
				$product_image_types=$helper->get_dirname($path.'product/'.$value.'/');	 
				foreach($product_image_types as $key=>$val){
					$type=$val;
					$product_image_paths=$helper->get_dirname($path.'product/'.$value.'/'.$val.'/');
					$result=$this->getImage($value,$val);
					$count=count($result); 
		 			if($count==0){
						$productImageId=0;
					}else{
						$productImageId=$result[0]->id;
					}
						foreach($product_image_paths as $num=>$v){							
						if($val=='default'){
							$product_id=$value;		 
							$suffixa=substr(strrchr($v, '.'), 1);
							$src_img=$path.'product/'.$value.'/'.$val.'/'.$v;
							$dst_img=$path.'product/'.$value.'/'.$val.'/'.$value.'defaults.'.$suffixa;
							$helper->img2thumb($src_img, $dst_img,$width = 200, $height = 100, $cut = 0, $proportion = 0);
							$image_path=$src_img.'#'.$dst_img;
						}else{
							$suffixa=substr(strrchr($v, '.'), 1);
							$now_name=$value.$val.$num.'.'.$suffixa;
							rename($path.'product/'.$value.'/'.$val.'/'.$v,$path.'product/'.$value.'/'.$val.'/'.$now_name);
							if($num>0){
								$image_path=$path.'product/'.$value.'/'.$val.'/'.$now_name.'#'.$image_path ;
							}else{
								$image_path=$path.'product/'.$value.'/'.$val.'/'.$now_name;
							}
						} 
					  }
					if($productImageId>0){
						$this->update($productImageId,$image_path);	
					}else{
						 $this->store($image_path,$value,$type);	
					} 
				}
			}				 
		}			
	 }
	}
		
}
