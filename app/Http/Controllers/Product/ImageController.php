<?php
/**
 * 图片控制器
 * 处理图片相关的Request与Response
 *
 * User: tup
 * Date: 16/1/4
 * Time: 下午5:02
 */

namespace App\Http\Controllers\product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Product\ImageRepository;

class ImageController extends Controller
{
    protected $image;

    public function __construct(Request $request, ImageRepository $image)
    {
        $this->request = $request;
        $this->image = $image;
        $this->mainIndex = route('productImage.index');
        $this->mainTitle = '图片';
    }

    /**
     * 图片列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->request->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
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
            'metas' => $this->metas(__FUNCTION__),
            'image' => $this->image->get($id),
        ];
        return view('product.image.show', $response);
    }

    /**
     * 添加产品图片
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'imageType' => config('product.image.types'),
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
        if ($this->request->isMethod('post')) {
            $data = $this->request->all();
            $files = $this->request->file();
            $this->image->createImage($data, $files);
        }
        return redirect($this->mainIndex);
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
            'metas' => $this->metas(__FUNCTION__),
            'image' => $this->image->get($id),
            'imageType' => config('product.image.types'),
        ];
        return view('product.image.edit', $response);
    }

    /**
     * 图片更新
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update()
    {
        if ($this->request->isMethod('post')) {
            $data = $this->request->all();
            $file = $this->request->file('map');
            $this->image->updateImage($data, $file);
        }
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
        $this->image->destroyImage($id);
        return redirect($this->mainIndex);
    }

}









