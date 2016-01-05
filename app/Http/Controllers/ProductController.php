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
			$default_image=$result[0]->path;
			$default_map=explode("#",$default_image);
			}
			
        $response = [
            'product' => $this->product->findOrFail($id),
			'product_image'=>$default_map[1],
			'product_imageType'=>$this->productrepository->getImage_types($id),
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
		$imageType=['default','original','choies','aliexpress','amazon','ebay','wish','Lazada'];
        $response = [
            'imageType' => $imageType,
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
	/**
     * 单个产品图片上传
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */		 
    public function imageUpdate($id)
    { 
		if($this->request->isMethod('post')){
			$request=$this->request;
			if($request->uploadType =='1'){
				$res=$this->productrepository->imageUpdate($request);
				}elseif($request->uploadType =='2'){
				$res=$this->productrepository->zipUpdate($request);
			}
		}
 		$request=$this->request->flash();
        return redirect(route('product.index'));
    }
	/**
     * 压缩包批量上传图片
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function zipUpload(){
	//echo 111;exit;
		$request=$this->request;
		$res=$this->productrepository->zipsUpload($request);
	 	return redirect(route('product.index'));
		}
		
}









