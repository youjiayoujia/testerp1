<?php

/**
 * 通关报关控制器
 * @author: tupgu
 * Date: 2016-1-4 10:46:32
 */
namespace App\Http\Controllers\CustomsClearance;

use App\Models\ProductModel;
use App\Http\Controllers\Controller;

class CustomsClearanceController extends Controller
{

    public function __construct(ProductModel $product)
    {
        $this->model = $product;
        $this->mainIndex = route('customsClearance.index');
        $this->mainTitle = '通关报关';
        $this->viewPath = 'customsClearance.';
    }

  
   /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
        ];
		$response['metas']['mainTitle']='通关报关';
		$response['metas']['title']='home';
        return view($this->viewPath . 'index', $response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $this->model->createProduct(request()->all(),request()->files);

        return redirect($this->mainIndex);
    }

}
