<?php

namespace App\Http\Controllers\Product\Channel;

use App\Models\ProductModel;
use App\Models\Product\channel\amazonProductModel;
use App\Http\Controllers\Controller;

class AmazonController extends Controller
{

    public function __construct(amazonProductModel $amazonProductModel,ProductModel $productModel)
    {
        $this->mainIndex = route('amazonProduct.index');
        $this->model = $amazonProductModel;
        $this->product = $productModel;
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
        echo 222;exit;
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
        //
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
        echo 8374;exit;
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
            if(empty($productModel->amazonProduct)){
                $data['choies_info'] = 'u are stupid';
                $data['product_id'] = $product_id;
                $this->model->create($data);
            }

        }

        return redirect($this->mainIndex);
    }
}
