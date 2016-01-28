<?php

/**
 * 产品管理控制器
 * @author: youjia
 * Date: 2016-1-4 10:46:32
 */
namespace App\Http\Controllers;

use App\Models\ProductModel;

class ProductController extends Controller
{

    protected $product;

    public function __construct(ProductModel $product)
    {
        $this->model = $product;
        $this->mainIndex = route('product.index');
        $this->mainTitle = '产品';
        $this->viewPath = 'product.';
    }



    /**
     * 新增产品界面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'catalogs' => $this->product->getCatalogs(),
            'models' => $this->product->getModels(),
            'suppliers' => $this->supplier->getSupplier(),
        ];

        return view('product.create',$response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*public function store(Request $request)
    {
        $this->request->flash();
        $this->validate($this->request, $this->product->rules('create'));   
        $this->product->create($this->request->all(),$this->request->files);

        return redirect($this->mainIndex);
    }*/

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'product' => $this->product->get($id),
        ];

        return view('product.show', $response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->product->get($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'catalogs' => $this->product->getCatalogs(),
            'product' => $product,
            'second_supplier_id' => $this->product->getSecondSupplier($product->second_supplier_id),
            'suppliers' => $this->supplier->getSupplier(),
            'attributes' => $this->product->getAttributes($id,'Attribute'),
            'features' => $this->product->getAttributes($product->spu_id,'Feature'),
        ];

        return view('product.edit', $response);
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
        $this->request->flash();
        $this->validate($this->request, $this->product->rules('update',$id));
        $this->product->update($id,$this->request->all(),$this->request->files);

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
        $this->product->destroy($id);
        return redirect($this->mainIndex);
    }

    /**
     * ajax获得品类属性
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCatalogProperty()
    {   
        $catalog_id = $_GET['catalog_id'];
        $product_id = isset($_GET['product_id'])?$_GET['product_id']:0;
        $data = $this->product->getCatalogProperty($catalog_id,$product_id);

        echo json_encode($data);
    }

    /**
     * 产品审核
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examine()
    {   
        $product_ids = isset($_GET['product_ids'])?$_GET['product_ids']:'';
        $product_id_arr = explode(',', $product_ids);
        $this->product->createItem($product_id_arr);

        echo json_encode(1);
    }
}
