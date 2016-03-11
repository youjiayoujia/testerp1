<?php
/**
 * 图片控制器
 * 处理图片相关的Request与Response
 *
 * User: tup
 * Date: 16/1/4
 * Time: 下午5:02
 */

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\product\ImageModel;

class ImageController extends Controller
{

    public function __construct(ImageModel $image)
    {
        $this->model = $image;
        $this->mainIndex = route('productImage.index');
        $this->mainTitle = '产品图片';
		$this->viewPath = 'product.image.';
    }
    
	
	public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
        ];
        return view($this->viewPath . 'index', $response);
    }
    /**
     * 图片上传
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $this->model->imageCreate(request()->all(), request()->files);

        return redirect($this->mainIndex);
    }


    /**
     * 图片更新
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('update'));
        $this->model->updateImage($id, request()->file('image'));
        return redirect($this->mainIndex);
    }

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $this->model->imageDestroy($id);
        return redirect($this->mainIndex);
    }

}









