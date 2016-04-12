<?php

namespace App\Http\Controllers\Product;

use App\Models\ProductModel;
use App\Models\Product\channel\amazonProductModel;
use App\Models\Product\channel\ebayProductModel;
use App\Models\Product\channel\aliexpressProductModel;
use App\Models\Product\channel\b2cProductModel;
use App\Models\Product\SupplierModel;
use App\Http\Controllers\Controller;

class ExamineProductController extends Controller
{

    public function __construct(amazonProductModel $amazonProductModel,ProductModel $productModel,SupplierModel $supplier)
    {
        $this->mainIndex = route('ExamineProduct.index');
        $this->channelProduct = $amazonProductModel;
        $this->product = $productModel;
        $this->supplier = $supplier;
        $this->mainTitle = '选款审核';
        $this->viewPath = 'product.examineProduct.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [
            'metas' => $this->metas('index'),
            'data' => $this->autoList($this->product->where('status','>=','0')),
        ];

        return view( $this->viewPath .'index', $response);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $this->product->find($id),
            'suppliers' =>$this->supplier->all(),
        ];

        return view($this->viewPath . 'edit', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        request()->flash();
        //$this->validate(request(), $this->model->rules('update',$id));
        $examineStatus = request()->input('examine');
        $productModel = $this->product->find($id);
        $data['examine_status'] = $examineStatus;
        $productModel->update($data);

        return redirect($this->mainIndex);
        
        
    }

    
     
}
