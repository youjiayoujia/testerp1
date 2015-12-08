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
use App\Repositories\ProductRepository;
 

class ProductController extends Controller
{
    protected $product;

    public function __construct(Request $request, ProductRepository $product)
    {
        $this->request = $request;
        $this->product = $product;
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
            'columns' => $this->product->columns,
            'data' => $this->product->index($this->request),
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
    {
        $response = [
            'product' => $this->product->detail($id),
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
        $response = [
            'brands' => $this->product->getBrands()
        ];

        return view('product.create', $response);
    }
	

    /**
     * 产品存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->product->rules);
        $this->product->store($this->request);

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
            'brands' => $this->product->getBrands(),
            'product' => $this->product->edit($id),
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
        $this->validate($this->request, $this->product->rules);
        $this->product->update($id, $this->request);

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
    {   
		$image_type=['default','original','choies','aliexpress','amazon','ebay','wish','Lazada'];
        $response = [
            'image_type' => $image_type,
            'product' => $this->product->edit($id),
        ];
        return view('product.addimage', $response);
    }
	
	 /**
     * 产品图片添加
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addzip()
    {   
		$image_type=['default','original','choies','aliexpress','amazon','ebay','wish','Lazada'];
        $response = [
            'image_type' => $image_type,
        ];
        return view('product.addzip', $response);
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
		/* $result=$this->product->getImage($product_id,$type); 
		 $path='storage/uploads/product/'.$product_id.'/'.$type.'/';
		 if(!is_dir($path)){	
		 	mkdir(iconv("UTF-8", "GBK", $path),0777,true);
		 } 
		 $image_path='';*/
		 
/*		 if(is_dir($path)){
			 for($i=0;$i<6;$i++){
			$file = $this->request->file('map'.$i);								
			if($this->request->hasFile('map'.$i)){
				$clientName = $file -> getClientOriginalName();
				$suffix=substr(strrchr($clientName, '.'), 1);
				if($suffix == 'jpg' || $suffix == 'jpeg' || $suffix == 'png' || $suffix == 'gif'){					
				$nownanme=$product_id.$type.$i.'.'.$suffix;
				//echo $nownanme;exit;
				$is_image_upload=strpos($result['0']->image_path,$nownanme);
				if($is_image_upload ==false)
				{
					$file->move($path,$nownanme);
				}else{
					echo '改图片已经上传过了！';
					//return redirect(route('product.index')); 	
				} 
				if($i==0){
				 $image_path=$path.$nownanme;
				 }else{ 
 				$image_path=$path.$nownanme.'#'.$image_path;
				}			
				}else{
				echo '上传出错！';exit;
				}
				}else{
					echo '请上传正确的格式！';exit;
					}
			}		 
		 }else{
			echo '文件夹创建失败！';exit; 
		 }*/
			$path='storage/uploads/zip/';
			/*mkdir(iconv("UTF-8", "GBK", $path),0777,true);*/
			$file = $this->request->file('zip');								
			if($this->request->hasFile('zip')){
				$clientName = $file -> getClientOriginalName();
				 
				$suffix=substr(strrchr($clientName, '.'), 1);
				if($suffix == 'zip' || $suffix == 'rar' || $suffix == '7z' || $suffix == 'cab'){	
				 			
				$nownanme=$product_id.$type.'.'.$suffix; 
				$file->move($path,$nownanme);
				$path='storage/uploads/zip/'.$nownanme;	
				$this->decompression($path);
				}
				}
			 
		 
		//$this->product->update_image($image_path,$this->request);
        return redirect(route('product.index'));
    }
	
/**
     * 文件解压
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function decompression($path){
		//echo $path;exit;
		$this->request->flash();
		$zip = $this->request->ZipArchive;//新建一个ZipArchive的对象
		/*
		通过ZipArchive的对象处理zip文件
		$zip->open这个方法的参数表示处理的zip文件名。
		如果对zip文件对象操作成功，$zip->open这个方法会返回TRUE
		*/
		if ($this->request->open($path) === TRUE) 
		{
		$this->request->extractTo('storage/uploads/product/');//假设解压缩到在当前路径下images文件夹的子文件夹php
		$this->request->close();//关闭处理的zip文件
		}
		}
	public function zip_upload(){
		$this->request->flash();
		
		}
}