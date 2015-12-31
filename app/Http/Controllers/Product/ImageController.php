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

    public function __construct(Request $request, ImageRepository $image)
    {
        $this->request = $request;
		$this->image =$image;
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
            'data' => $this->image->auto()->paginate(),
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
        $response = [
            'image' =>$this->image->get($id),
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
        $response = [
            'image_type' =>  config('imageType.imageType'),
        ];
        return view('product.image.create', $response);
    }
	
	 /**
     * 新增图片上传
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
       if($this->request->isMethod('post')){
			$data=$this->request->all();
			$files=$this->request->file();
			$this->image->createImage($data,$files);	 
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
			$file=$this->request->file('map');
			$this->image->updateImage($data,$file);	 
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
        $response = [
            'image' =>$this->image->get($id),
			'imageType' =>  config('imageType.imageType'),
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
        $this->image->destroyImage($id);
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
			$res=$this->image->zipsUpload($request);
			return redirect(route('productImage.index'));
		}else{
			return view('product.image.addzip');
			}
		}
		
}









