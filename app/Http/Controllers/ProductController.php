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

class ProductController extends Controller
{
    protected $product;

    public function __construct(Request $request, Product $product)
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
            'data' => $this->product->getList(),
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
            'product' => $this->product->findOrFail($id),
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
}