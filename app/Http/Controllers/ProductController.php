<?php

/**
 * 产品控制器
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
}