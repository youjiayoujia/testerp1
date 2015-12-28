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
    protected $product;

    public function __construct(Request $request, ImageRepository $imagerepository)
    {
        $this->request = $request;
		$this->imagerepository =$imagerepository;
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
            'data' => $this->imagerepository->auto()->paginate(),
        ];

        return view('product.image.index', $response);
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
		$result=$this->imagerepository->getImage($id,$type);		 
		if(isset($result[0])){
			$default_image=$result[0]->image_path;
			$default_map=explode("#",$default_image);
			}
			
        $response = [
            'product' => $this->product->findOrFail($id),
			'product_image'=>$default_map[1],
			'product_image_type'=>$this->imagerepository->getImageTypes($id),
        ];

        return view('product.image.show', $response);
    }
	
	/**
     * 产品创建
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

        return redirect(route('product.image.index'));
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
        return view('product.image.edit', $response);
    }

    /**
     * 图片上传
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update()
    {
       if($this->request->isMethod('post')){
			$request=$this->request;
			if($request->uploadType =='1'){
				$res=$this->imagerepository->imageUpdate($request);
				}elseif($request->uploadType =='2'){
				$res=$this->imagerepository->zipUpdate($request);
			}
		}
 		$request=$this->request->flash();
        return redirect(route('productImage.index'));
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
        return redirect(route('product.image.index'));
    }
	
	
	 
	 /**
     * 产品图片添加
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addzip()
    {  	
        return view('product.image.addzip');
    }
	 
	/**
     * 压缩包批量上传图片
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function zipUpload(){
		$request=$this->request;
		$res=$this->imagerepository->zipsUpload($request);
	 	return redirect(route('productImage.index'));
		}
		
}









