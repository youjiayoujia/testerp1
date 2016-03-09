<?php

namespace App\Http\Controllers\Product\Channel;

use App\Models\ProductModel;
use App\Models\Product\channel\amazonProductModel;
use App\Models\Product\SupplierModel;
use App\Http\Controllers\Controller;

class AmazonController extends Controller
{

    public function __construct(amazonProductModel $amazonProductModel,ProductModel $productModel,SupplierModel $supplier)
    {
        $this->mainIndex = route('amazonProduct.index');
        $this->model = $amazonProductModel;
        $this->product = $productModel;
        $this->supplier = $supplier;
        $this->mainTitle = '亚马逊选中产品';
        $this->viewPath = 'product.channel.amazon.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_ids = request()->input('product_ids');
        $channel_id = request()->input('channel_id');
        if($product_ids){

        }
        $response = [
            'metas' => $this->metas('index'),
            'data' => $this->autoList($this->model),
        ];

        return view( $this->viewPath .'index', $response);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
              
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        echo 9313;exit;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo 8374;
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
            'model' => $this->model->find($id),
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
        $amazonProductModel = $this->model->find($id);
        $amazonProductModel->updateAmazonProduct(request()->all());
        return redirect($this->mainIndex);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 产品选中
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function beChosed()
    {
        $channel_id = request()->input('channel_id');   
        $product_id_arr = request()->input('product_ids');
        //创建item
        foreach($product_id_arr as $product_id){
            $productModel = $this->product->find($product_id);
            if(empty($productModel->item->toArray())){
                $productModel->createItem();
            }
            $data = [];
            
            if(count($productModel->amazonProduct)==0){           
                $this->model->createAmazonProduct($productModel->toArray());
            }
        }

        return redirect($this->mainIndex);
    }

    /**
     * 产品图片编辑
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function amazonProductEditImage()
    {
        $id = request()->input('id');
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $this->model->find($id),
        ];

        return view($this->viewPath . 'editImage', $response);
    }

    /**
     * 产品图片编辑
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function amazonProductUpdateImage()
    {
        $id = request()->input('id');
        request()->flash();
        //$this->validate(request(), $this->model->rules('update',$id));
        $amazonProductModel = $this->model->find($id);
        $this->model->updateAmazonProductImage(request()->files);
        
    }

    /**
     * 产品审核
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examineAmazonProduct()
    {
        $id = request()->input('product_ids');
        $amazonProductModel = $this->model->find($id);
        $this->model->examineAmazonProduct($id);    
    }
     

    /**
     * 产品取消审核
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelExamineAmazonProduct()
    {
        $id = request()->input('product_ids');
        $amazonProductModel = $this->model->find($id);
        $amazonProductModel->cancelExamineAmazonProduct();    
    }
}
