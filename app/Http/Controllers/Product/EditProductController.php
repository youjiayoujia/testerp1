<?php

namespace App\Http\Controllers\Product;

use App\Models\ProductModel;
use App\Models\Product\channel\amazonProductModel;
use App\Models\Product\channel\ebayProductModel;
use App\Models\Product\channel\aliexpressProductModel;
use App\Models\Product\channel\b2cProductModel;
use App\Models\Product\productEnglishValueModel;
use App\Models\Product\SupplierModel;
use App\Http\Controllers\Controller;

class EditProductController extends Controller
{

    public function __construct(amazonProductModel $amazonProductModel,ProductModel $productModel,SupplierModel $supplier)
    {
        $this->mainIndex = route('EditProduct.index');
        $this->channelProduct = $amazonProductModel;
        $this->product = $productModel;
        $this->supplier = $supplier;
        $this->mainTitle = '选款产品编辑';
        $this->viewPath = 'product.editProduct.';
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
            'data' => $this->autoList($this->product->where('status','=','1')),
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
        $editStatus = request()->input('edit');
        $data = request()->all();
        $data['edit_status'] = $editStatus;
        $productModel = $this->product->find($id);
        $productModel->update($data);

        //更新英文信息
        $productEnglishValueModel = new productEnglishValueModel();
        $data['product_id'] = $productModel->id;
        $english = $productEnglishValueModel->firstOrNew(['product_id'=>$id]);
        //如果没保存过对应产品ID的英文资料,create，否则就更新
        if(count($english->toArray())==1){
            $english->create($data);
        }else{
            $english->update($data);
        }
        
        return redirect($this->mainIndex);
    }

    /**
     * 产品图片编辑界面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productEditImage()
    {
        $id = request()->input('id');
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $this->product->find($id),
        ];

        return view($this->viewPath . 'editImage', $response);
    }

    /**
     * 产品图片编辑
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productUpdateImage()
    {
        $id = request()->input('id');
        request()->flash();
        $ProductModel = $this->product->find($id);
        $ProductModel->updateProductImage(request()->all(),request()->files);

        return redirect($this->mainIndex);
    }

    /**
     * 产品审核
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examineProduct()
    {
        $id = request()->input('product_id');
        $status = request()->input('status');
        $productModel = $this->product->find($id);
        $productModel->examineProduct($status);

        return 1;
    }
     
}
