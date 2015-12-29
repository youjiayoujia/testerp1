<?php

namespace App\Repositories\Product;

use App\Base\BaseRepository;
use App\Models\product\Product_imageModel;
use App\helps\Sort;
use Chumper\Zipper\Zipper;
use Folklore\Image\Facades\Image;
/**
 * 范例: 产品库
 *
 * @author Vincent<nyewon@gmail.com>
 */
class ImageRepository extends BaseRepository
{
	protected $searchFields = ['id','product_id', 'user_id', 'type'];
    public $rules = [
        'create' => [
					'product_id' => 'required',
					'type' => 'required',
					'user_id'=>'required',
		],
        'update' => [
					'product_id' => 'required',
					'type' => 'required',
					'user_id'=>'required',
		]
    ];
	 

    public function __construct(Product_imageModel $product_image)
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
    public function store($imagePath,$productId,$type)
    {	
        $data['type'] = $type;
        $data['product_id'] = $productId;
        $data['user_id'] = 1;
        $data['image_path'] = $imagePath;
        return $this->create($data);
    }
	
	/**
     * 更新产品图片
     *
     * @param int $id 产品ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function update($id, $imagePath)
    {	echo $imagePath;
       $data['user_id']= 1;
       $data['image_path'] = $imagePath;
       return $this->update($id, $data);exit;
    }
 
	/**
     * 获取默认图片
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getImageTypes($productId)
    {
		 
        return $this->model->whereRaw('product_id='.$productId)->get();
    }
	 /**
     * 查询图片是否已上传
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getImage($productId,$type)
    {
        return $this->model->whereRaw('product_id=? and type=?',[$productId,$type])->get();
    }

	/**
	 * 上传图片
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function imageUpdate($request){
		$productId=$request->id;
		//echo $productId;
		$type=$request->type; 
		$result=$this->getImage($productId,$type);
		$count=count($result); 
		 if($count==0){
			$productImageId=0;
		 }else{
			$productImageId=$result[0]->id;
			 }
		 $path='storage/uploads/product/'.$productId.'/'.$type.'/';
		 if(!is_dir($path)){	
		 	mkdir(iconv("UTF-8", "GBK", $path),0777,true);
		 } 	 
		 if(is_dir($path)){
			 if($type=='default'){
			 $this->defaultImageUpload($request,$productId,$type,$productImageId,$path);
		 }else{	 
			 $this->UploadImage($request,$productId,$type,$productImageId,$path);
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
	public function defaultImageUpload($request,$productId,$type,$productImageId,$path){
		$imagePath='';
		 $file=$request->file('map0');
		 if($request->hasFile('map0')){
				$suffix = $file -> getClientOriginalExtension();	
				if($suffix == 'jpg' || $suffix == 'jpeg' || $suffix == 'png' || $suffix == 'gif'){					
				$nownanme=$productId.$type.'.'.$suffix;
					$file->move($path,$nownanme);			 
					}
					$srcImg =$path.$nownanme;
					$dstImg = $path.$productId.$type.'s.'.$suffix;
					$imagePath=$srcImg.'#'.$dstImg;	 
					Image::make($srcImg,array('width' => 200,'height' => 300,))->save($dstImg); 
						if($productImageId>0){
							$this->update($productImageId,$imagePath);	
						}else{
							$this->store($imagePath,$productId,$type);	
						}   
		 }
		
		}	
	/**
	*处理多平台图片上传
	*
	*
	*/
	public function UploadImage($request,$productId,$type,$productImageId,$path){
		$imagePath='';
		 for($i=0;$i<6;$i++){
			$file = $request->file('map'.$i);								
			if($request->hasFile('map'.$i)){
				$suffix=$file -> getClientOriginalExtension();
					if($suffix == 'jpg' || $suffix == 'jpeg' || $suffix == 'png' || $suffix == 'gif'){					
						$nownanme=$productId.$type.$i.'.'.$suffix;
						$file->move($path,$nownanme);			 
						if($i==0){
							$imagePath=$path.$nownanme;
						 }else{ 
							$imagePath=$path.$nownanme.'#'.$imagePath;
						}			
					}else{
						echo '请上传正确的图片格式！';exit;
					}
			}else{
					echo '上传出错！';exit;
			}
		}	 
		if($productImageId>0){
			//echo '产品'.$productId.'的'.$type.'类型图片已上传过！'; 
			$this->update($productImageId,$imagePath);	
		}else{
			$this->store($imagePath,$productId,$type);	
		} 
	}
		
		
	/**
	 * 上传压缩包
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function zipUpdate($request){
		$productId=$request->id; 
		$helper= new Sort;
		$type=$request->type;
		$file = $request->file('zip');
			$zipPath='storage/uploads/zip/';								
			if($request->hasFile('zip')){
				$clientName = $file -> getClientOriginalName();
				$suffix=substr(strrchr($clientName, '.'), 1);			 
				 if($clientName!=$productId.'.'.$suffix){
					 echo '请上传正确的文件压缩包！';exit;
					 }
				if($suffix == 'zip' || $suffix == 'rar' || $suffix == '7z' || $suffix == 'cab'){					 			
				$nownanme=$productId.$type.'.'.$suffix; 
				$file->move($zipPath,$nownanme);
				$zippath='storage/uploads/zip/'.$nownanme;	
				$path='storage/uploads/product/';
				$zipper = new Zipper;
				$zipper->make($zippath)->extractTo($path); 
				  $dir_path=$path.$productId.'/';				 
				  $dir_name=$helper->get_dirname($dir_path);
				  foreach($dir_name as $key=>$value){
						  $dirPathType='storage/uploads/product/'.$productId.'/'.$value.'/';
						  $imagePaths[$value]=$helper->get_dirname($dirPathType);	 
					  }
				 foreach($imagePaths as $key=>$val){
						 $type=$key;
						 foreach($val as $k=>$v){
						 $orname=$path.$productId.'/'.$key.'/'.$v;
						 $nowPath='storage/uploads/product/'.$productId.'/'.$key.'/';
						 if($key=='default'){
							  $suffixa=substr(strrchr($v, '.'), 1);
							  $nowName=$productId.'default.'.$suffixa;
							  rename($orname,$nowPath.$nowName);
								$srcImg =$nowPath.$nowName;
								$dstImg = $nowPath.$productId.'defaults.'.$suffixa;
								$imagePath=$srcImg.'#'.$dstImg;
								Image::make($srcImg,array('width' => 200,'height' => 300,))->save($dstImg); 
							 }else{ 
								 $suffixa=substr(strrchr($v, '.'), 1);
								 $nowName=$productId.$key.$k.'.'.$suffixa;
								 rename($orname,$nowPath.$nowName);
									 if($k>0){
									 	$imagePath=$nowPath.$nowName.'#'.$imagePath;
									 }else{
									 	$imagePath=$nowPath.$nowName;
									 }
								 }
						 }
						 $result=$this->getImage($productId,$type);
						 $count=count($result); 
		 				 if($count==0){
							$productImageId=0;
						 }else{
							$productImageId=$result[0]->id;
						 }	 
						 if($productImageId>0){
							 echo '产品'.$productId.'的'.$type.'类型图片已上传过！';
							//$this->update($productImageId,$imagePath);	
							}else{	
							$this->store($imagePath,$productId,$type);	
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
		$helper= new Sort;
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
			$productIds=$helper->get_dirname($path.'product/');
			foreach($productIds as $key=>$value){
				$productImageTypes=$helper->get_dirname($path.'product/'.$value.'/');	 
				foreach($productImageTypes as $key=>$val){
					$type=$val;
					$productImagePaths=$helper->get_dirname($path.'product/'.$value.'/'.$val.'/');
					$result=$this->getImage($value,$val);
					$count=count($result); 
		 			if($count==0){
						$productImageId=0;
					}else{
						$productImageId=$result[0]->id;
					}
						foreach($productImagePaths as $num=>$v){							
						if($val=='default'){
							$productId=$value;		 
							$suffixa=substr(strrchr($v, '.'), 1);
							$srcImg=$path.'product/'.$value.'/'.$val.'/'.$v;
							$dstImg=$path.'product/'.$value.'/'.$val.'/'.$value.'defaults.'.$suffixa;
							Image::make($srcImg,array('width' => 200,'height' => 300,))->save($dstImg); 
							$imagePath=$srcImg.'#'.$dstImg;
						}else{
							$suffixa=substr(strrchr($v, '.'), 1);
							$nowName=$value.$val.$num.'.'.$suffixa;
							rename($path.'product/'.$value.'/'.$val.'/'.$v,$path.'product/'.$value.'/'.$val.'/'.$nowName);
							if($num>0){
								$imagePath=$path.'product/'.$value.'/'.$val.'/'.$nowName.'#'.$imagePath ;
							}else{
								$imagePath=$path.'product/'.$value.'/'.$val.'/'.$nowName;
							}
						} 
					  }
					if($productImageId>0){
						echo '产品'.$value.'的'.$type.'类型图片已上传过！';
						//$this->update($productImageId,$imagePath);	
					}else{
						 $this->store($imagePath,$value,$type);
					} 
				}
			}				 
		}			
	 }
	}
		
}
