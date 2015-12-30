<?php

/**
 * 产品控制器
 * 处理产品相关的Request与Response
 *
 * User: Vincent
 * Date: 15/11/17
 * Time: 下午5:02
 */

namespace App\Http\Controllers\product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Product\ImageRepository;
use Chumper\Zipper\Zipper;

class ImageController extends Controller
{
    protected $image;

    public function __construct(Request $request, ImageRepository $imageRepository)
    {
        $this->request = $request;
		$this->imageRepository =$imageRepository;
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
            'data' => $this->imageRepository->auto()->paginate(),
        ];

        return view('product.image.index', $response);
    }
	
	
    /**
     * 图片详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {	
		$result=$this->imageRepository->get($id); 	 
		if(isset($result['image_name'])){
			$imageName=$result['image_name'];
			$images=explode("#",$imageName);
			}	
			//var_dump($images);exit;	
        $response = [
            'image' =>$this->imageRepository->get($id),
			'images'=>$images,
        ];

        return view('product.image.show', $response);
    }
	
	/**
     * 新图上传
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
		$image_type=['default','original','choies','aliexpress','amazon','ebay','wish','Lazada'];
        $response = [
            'image_type' => $image_type,
        ];
        return view('product.image.create', $response);
    }
	
	 /**
     * 图片上传
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
       if($this->request->isMethod('post')){
			$data=$this->request->all();
			$files=$this->request->file();
			$this->imageRepository->store($data,$files);	 
		}
        return redirect(route('productImage.index'));
    }
	

    /**
     * 跟新
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update()
    {
        if($this->request->isMethod('post')){
			$data=$this->request->all();
			$files=$this->request->file();
			$this->imageRepository->updateImage($data,$files);	 
		}
        return redirect(route('productImage.index'));
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    { 
		$result=$this->imageRepository->get($id); 	 
		if(isset($result['image_name'])){
			$imageName=$result['image_name'];
			$images=explode("#",$imageName);
			}
        $response = [
            'image' =>$this->imageRepository->get($id),
			'images'=>$images,
        ];
        return view('product.image.edit', $response); 
    }

   

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $this->imageRepository->destroyImage($id);
        return redirect(route('productImage.index'));
    }
	
	/**
     * 压缩包批量上传图片
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function zipUpload()
	{
		if($this->request->isMethod('post')){
			$request=$this->request;
			$res=$this->imageRepository->zipsUpload($request);
			return redirect(route('productImage.index'));
		}else{
			return view('product.image.addzip');
			}
		}
		
}









