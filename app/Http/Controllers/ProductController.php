<?php

/**
 * 产品控制器
 * 处理产品相关的Request与Response
 *
 * User: Vincent
 * Date: 15/11/17
 * Time: 下午5:02
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Chumper\Zipper\Zipper;
 
class ProductController extends Controller
{
    protected $product;

    public function __construct(Request $request, Product $product, ProductRepository $productrepository)
    {
        $this->request = $request;
        $this->product = $product;
		$this->productrepository =$productrepository;
    }

    /**
     * 产品列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->request->flash();
        $response = [
            'data' => $this->product->paginate(),
        ];

        return view('product.index', $response);
    }
	
	
    /**
     * 产品详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {	//$this->request->flash();
		$type='default';
		$result=$this->productrepository->getImage($id,$type);		 
		if(isset($result[0])){
			$default_image=$result[0]->image_path;
			$default_map=explode("#",$default_image);
			}
			
        $response = [
            'product' => $this->product->findOrFail($id),
			'product_image'=>$default_map[1],
			'product_image_type'=>$this->productrepository->getImage_types($id),
        ];

        return view('product.show', $response);
    }
	
	/**
     * 产品创建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('product.create');
    }
	

    /**
     * 产品存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $this->request->flash();

        $rules = [
            'name' => 'required',
        ];
        $this->validate($this->request, $rules);

        $data = array();
        $data['name'] = $this->request->input('name');
        $data['c_name'] = $this->request->input('c_name');
        $this->product->create($data);

        return redirect(route('product.index'));
    }

    /**
     * 产品编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $response = [
            'product' => $this->product->findOrFail($id),
        ];
        return view('product.edit', $response);
    }

    /**
     * 产品更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        $this->request->flash();

        $rules = [
            'name' => 'required',
        ];
        $this->validate($this->request, $rules);
        
        $product = $this->product->findOrFail($id);
        $product->name = $this->request->input('name');
        $product->c_name = $this->request->input('c_name');
        $product->save();

        return redirect(route('product.index'));
    }

    /**
     * 产品删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $this->product->destroy($id);
        return redirect(route('product.index'));
    }
	
	
	 /**
     * 产品图片添加
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addimage($id)
    {   $this->request->flash();
		$image_type=['default','original','choies','aliexpress','amazon','ebay','wish','Lazada'];
        $response = [
            'image_type' => $image_type,
            'product' => $this->product->findOrFail($id),
        ];
		//var_dump($response);exit;
        return view('product.addimage', $response);
    }
	
	 /**
     * 产品图片添加
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addzip()
    {   		
        return view('product.addzip');
    }

	/**
     * 产品更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	 public function product_image_ajax(){
		 //var_dump($_REQUEST);exit;
		$this->request->flash();
		$zip = $this->request->ZipArchive;
		/*
		$zip->open这个方法第一个参数表示处理的zip文件名。
		第二个参数表示处理模式，ZipArchive::OVERWRITE表示如果zip文件存在，就覆盖掉原来的zip文件。
		如果参数使用ZIPARCHIVE::CREATE，系统就会往原来的zip文件里添加内容。
		如果不是为了多次添加内容到zip文件，建议使用ZipArchive::OVERWRITE。
		使用这两个参数，如果zip文件不存在，系统都会自动新建。
		如果对zip文件对象操作成功，$zip->open这个方法会返回TRUE
		*/
		/*if ($this->request->ZipArchive->open('test.zip', $this->request->ZipArchive->OVERWRITE) === TRUE)
		{
		$this->request->ZipArchiveaddFile('image.txt');//假设加入的文件名是image.txt，在当前路径下
		$this->request->ZipArchiveclose();
		} */
		 
		 }
    public function image_update()
    {  
		 $this->request->flash();
		 $product_id=$this->request->product_id;
		 $type=$this->request->type;
		 
		
		if($this->request->file('map0')){
			$result=$this->productrepository->getImage($product_id,$type);
		 //var_dump($result);exit;
		 if(!isset($result)){
			$product_image_id=$result[0]->id;
		 }else{
			$product_image_id=0;
			 }
		if($type=='default'){
			$path='storage/uploads/product/'.$product_id.'/';
			}else{	
		 	$path='storage/uploads/product/'.$product_id.'/'.$type.'/';
		 }
		 if(!is_dir($path)){	
		 	mkdir(iconv("UTF-8", "GBK", $path),0777,true);
		 } 
		 $image_path='';	 
		 if(is_dir($path)){
			 if($type=='default'){
			 if($this->request->file('map0')){ 
		 $file=$this->request->file('map0');
		 if($this->request->hasFile('map0')){
				$clientName = $file -> getClientOriginalName();
				$suffix=substr(strrchr($clientName, '.'), 1);
				if($suffix == 'jpg' || $suffix == 'jpeg' || $suffix == 'png' || $suffix == 'gif'){					
				$nownanme=$product_id.$type.'.'.$suffix;
					$file->move($path,$nownanme);			 
					}
					$src_img =$path.$nownanme;
					$dst_img = $path.$product_id.$type.'s.'.$suffix;
					$image_path=$src_img.'#'.$dst_img;
					$stat = $this->img2thumb($src_img, $dst_img, $width = 200, $height = 300, $cut = 0, $proportion = 0);
					if($stat){
						echo 'Resize Image Success!<br />';
						if($product_image_id>0){
							$this->productrepository->update_image($product_image_id,$image_path);	
							}else{
							$this->productrepository->store_image($image_path,$product_id,$type);	
							}   
					}else{
						echo 'Resize Image Fail!';  exit;
					}

		 }
		 }
		 }else{	 
			 for($i=0;$i<6;$i++){
			$file = $this->request->file('map'.$i);								
			if($this->request->hasFile('map'.$i)){
				$clientName = $file -> getClientOriginalName();
				$suffix=substr(strrchr($clientName, '.'), 1);
				if($suffix == 'jpg' || $suffix == 'jpeg' || $suffix == 'png' || $suffix == 'gif'){					
				$nownanme=$product_id.$type.$i.'.'.$suffix;
					$file->move($path,$nownanme);			 
				if($i==0){
				 $image_path=$path.$nownanme;
				 }else{ 
 				$image_path=$path.$nownanme.'#'.$image_path;
				}			
				}else{
				echo '上传出错！';exit;
				}
				}else{
					echo '请上传正确的图片格式！';exit;
					}
			}
			 
			if($product_image_id>0){
			 
			$this->productrepository->update_image($product_image_id,$image_path);	
			}else{	
			$this->productrepository->store_image($image_path,$product_id,$type);	
			} 
		 }
		 }else{
			echo '文件夹创建失败！';exit; 
		 } 
		 
		 
		 }else{
			$file = $this->request->file('zip');
			$zip_path='storage/uploads/zip/';								
			if($this->request->hasFile('zip')){
				$clientName = $file -> getClientOriginalName();
				$suffix=substr(strrchr($clientName, '.'), 1);			 
				 if($clientName!=$product_id.'.'.$suffix){
					 echo '请上传正确的文件压缩包！';exit;
					 }
				if($suffix == 'zip' || $suffix == 'rar' || $suffix == '7z' || $suffix == 'cab'){					 			
				$nownanme=$product_id.$type.'.'.$suffix; 
				$file->move($zip_path,$nownanme);
				$zippath='storage/uploads/zip/'.$nownanme;	
				$path='storage/uploads/product/';//临时存放地址
				$res=$this->decompression($zippath,$path);
				
				  $dir_path=$path.$product_id.'/';
				  $dir_name=$this->get_dirname($dir_path);
				  //var_dump($dir_name);exit;
				  foreach($dir_name as $key=>$value){
					  if($value=='default.jpg' || $value=='default.jpeg' || $value=='default.png'){
						  $image_paths[0]=$value;
						  }else{
						  $dir_path_type='storage/uploads/product/'.$product_id.'/'.$value.'/';
						  $image_paths[$value]=$this->get_dirname($dir_path_type);	     
						  }
					  }
					  if(!is_dir('storage/uploads/product/'.$product_id)){
						 
							 mkdir(iconv("UTF-8", "GBK", 'storage/uploads/product/'.$product_id),0777,true);
							 }
					  //var_dump($image_paths);exit;
				 foreach($image_paths as $key=>$val){
					 //echo $key;exit;
					 
							 if($val==0){
								 $type='default';
								 
								 
								 $now_path='storage/uploads/product/'.$product_id.'/';
								 $orname='storage/uploads/product/'.$product_id.'/'.$val;
								  $suffixa=substr(strrchr($val, '.'), 1);
								  $now_name=$product_id.'default.'.$suffixa;
								  rename($orname,$now_path.$now_name);
								 //删除原图片
								    $src_img =$now_path.$now_name;
									$dst_img = $now_path.$product_id.'defaults.'.$suffixa;
									$image_path=$src_img.'#'.$dst_img;
									$stat = $this->img2thumb($src_img, $dst_img, $width = 200, $height = 300, $cut = 0, $proportion = 0);
								 }else{
									 $type=$key;
								 foreach($val as $k=>$v){
									 $orname=$path.$product_id.'/'.$key.'/'.$v;
									 $now_path='storage/uploads/product/'.$product_id.'/'.$key.'/';
									 
									 if(!is_dir($now_path)){
										 mkdir(iconv("UTF-8", "GBK", $now_path),0777,true);
										 }
									 $suffixa=substr(strrchr($v, '.'), 1);
									 $now_name=$product_id.$key.$k.'.'.$suffixa;
									 
									 rename($orname,$now_path.$now_name);
									 //删除图片
									 if($k>0){
									 $image_path=$now_path.$now_name.'#'.$image_path;
									 }else{
									 $image_path=$now_path.$now_name;
									 }
									 }
							 }
							 $result=$this->productrepository->getImage($product_id,$type);
							 //var_dump($result);exit;
							 if(!isset($result)){
								$product_image_id=$result[0]->id;
							 }else{
								$product_image_id=0;
								 }
							 
						 if($product_image_id>0){
							$this->productrepository->update_image($product_image_id,$image_path);	
							}else{
							$this->productrepository->store_image($image_path,$product_id,$type);	
							} 
				 
				 }
				}
				
				}
			 
		 }
		 
		 
        return redirect(route('product.index'));
    }
	/**
     * 文件上传
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	 
	 
	 
	/**
     * 文件解压
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function decompression($zippath,$path){
		$this->request->flash();
		$zipper = new \Chumper\Zipper\Zipper;
		$zipper->make($zippath)->extractTo($path);
		//$patha=realpath($zippath);
		//$root=chmod($patha, 0755);
		//echo $root;exit;
	 	//unlink($zippath);	 
		}
		/**
		*获取文件名
		*
		*/
	public function get_dirname($dir_path){
		$dir=opendir($dir_path);
		$dir_name=array();
		while (($file = readdir($dir)) !== false)
		  {
		   if($file!='.' && $file!='..'){
			  
			  $dir_name[]=$file;
			   }
		  }
		  closedir($dir);
		  return $dir_name;
		} 
		
	public function zip_upload(){
		$this->request->flash();
		$path='storage/uploads/zip/';
		$file = $this->request->file('zip');
		if($this->request->hasFile('zip')){
			$clientName = $file -> getClientOriginalName();
			$suffix=substr(strrchr($clientName, '.'), 1);
				if($suffix == 'zip' || $suffix == 'rar' || $suffix == '7z' || $suffix == 'cab'){	
				$file->move($path,$clientName);
				$zippath='storage/uploads/zip/'.$clientName;	
				$path='storage/uploads/';
				$res=$this->decompression($zippath,$path);
				$product_ids=$this->get_dirname($path.'product/');
				foreach($product_ids as $key=>$value){
					$product_image_types=$this->get_dirname($path.'product/'.$value.'/');
					 
					foreach($product_image_types as $key=>$val){
						//var_dump($val);
						if($val=='default.jpg' || $val=='default.jpeg' || $val=='default.png'){
							//var_dump($val);exit;
							$type='default';
							$product_id=$value;		
							$result=$this->productrepository->getImage($value,$type);
							if(!empty($result)){ 
								$product_image_id=0;
							}else{
								$product_image_id=$result[0]->id;
								}
							
							$suffixa=substr(strrchr($val, '.'), 1);
							$src_img=$path.'product/'.$value.'/'.$val;
							$dst_img=$path.'product/'.$value.'/'.$value.'defaults.'.$suffixa;
							$this->img2thumb($src_img, $dst_img,$width = 200, $height = 100, $cut = 0, $proportion = 0);
							$image_path=$src_img.'#'.$dst_img;
						}else{
							$type=$val;
							//var_dump($val);exit;
							$product_image_paths=$this->get_dirname($path.'product/'.$value.'/'.$val.'/');
							//var_dump($product_image_paths);exit;
							$result=$this->productrepository->getImage($value,$val);
							//var_dump($result); echo (empty($result));exit;
							if(!empty($result)){ 
								$product_image_id=0;
							}else{
								$product_image_id=$result[0]->id;
								}
							foreach($product_image_paths as $num=>$v){
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
						if($product_image_id>0){
							$this->productrepository->update_image($product_image_id,$image_path);	
							}else{
							 $this->productrepository->store_image($image_path,$value,$type);	
							} 
						}
					}//var_dump($product_image_types);exit;
					 
				}
				
			}
		}
		
	/**
     * 图片压缩
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */		
	public function img2thumb($src_img, $dst_img, $width = 200, $height = 100, $cut = 0, $proportion = 0)
	{
		if(!is_file($src_img))
		{
			return false;
		}
		$ot = $this->fileext($dst_img);
		$otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
		$srcinfo = getimagesize($src_img);
		$src_w = $srcinfo[0];
		$src_h = $srcinfo[1];
		$type  = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
		$createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);
	 
		$dst_h = $height;
		$dst_w = $width;
		$x = $y = 0;
	 
		/**
		 * 缩略图不超过源图尺寸（前提是宽或高只有一个）
		 */
		if(($width> $src_w && $height> $src_h) || ($height> $src_h && $width == 0) || ($width> $src_w && $height == 0))
		{
			$proportion = 1;
		}
		if($width> $src_w)
		{
			$dst_w = $width = $src_w;
		}
		if($height> $src_h)
		{
			$dst_h = $height = $src_h;
		}
	 
		if(!$width && !$height && !$proportion)
		{
			return false;
		}
		if(!$proportion)
		{
			if($cut == 0)
			{
				if($dst_w && $dst_h)
				{
					if($dst_w/$src_w> $dst_h/$src_h)
					{
						$dst_w = $src_w * ($dst_h / $src_h);
						$x = 0 - ($dst_w - $width) / 2;
					}
					else
					{
						$dst_h = $src_h * ($dst_w / $src_w);
						$y = 0 - ($dst_h - $height) / 2;
					}
				}
				else if($dst_w xor $dst_h)
				{
					if($dst_w && !$dst_h)  //有宽无高
					{
						$propor = $dst_w / $src_w;
						$height = $dst_h  = $src_h * $propor;
					}
					else if(!$dst_w && $dst_h)  //有高无宽
					{
						$propor = $dst_h / $src_h;
						$width  = $dst_w = $src_w * $propor;
					}
				}
			}
			else
			{
				if(!$dst_h)  //裁剪时无高
				{
					$height = $dst_h = $dst_w;
				}
				if(!$dst_w)  //裁剪时无宽
				{
					$width = $dst_w = $dst_h;
				}
				$propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
				$dst_w = (int)round($src_w * $propor);
				$dst_h = (int)round($src_h * $propor);
				$x = ($width - $dst_w) / 2;
				$y = ($height - $dst_h) / 2;
			}
		}
		else
		{
			$proportion = min($proportion, 1);
			$height = $dst_h = $src_h * $proportion;
			$width  = $dst_w = $src_w * $proportion;
		}
	 
		$src = $createfun($src_img);
		$dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
		$white = imagecolorallocate($dst, 255, 255, 255);
		imagefill($dst, 0, 0, $white);
	 
		if(function_exists('imagecopyresampled'))
		{
			imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
		}
		else
		{
			imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
		}
		$otfunc($dst, $dst_img);
		imagedestroy($dst);
		imagedestroy($src);
		return true;
	}
	/**
     * 图片压缩
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function fileext($file)
	{
		return pathinfo($file, PATHINFO_EXTENSION);
	}		
}