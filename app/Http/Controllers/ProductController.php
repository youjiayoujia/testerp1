<?php

/**
 * 产品管理控制器
 * @author: youjia
 * Date: 2016-1-4 10:46:32
 */
namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Models\CatalogModel;
use App\Models\Product\SupplierModel;
use App\Models\Product\ProductAttributeValueModel;
use App\Models\Product\ProductFeatureValueModel;

class ProductController extends Controller
{

    protected $product;

    public function __construct(ProductModel $product,SupplierModel $supplier,CatalogModel $catalog)
    {
        $this->model = $product;
        $this->supplier = $supplier;
        $this->catalog = $catalog;
        $this->mainIndex = route('product.index');
        $this->mainTitle = '产品';
        $this->viewPath = 'product.';
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'catalogs' => $this->catalog->all(),
            'models' => $this->model->getModels(),
            'suppliers' => $this->supplier->all(),
        ];
        return view($this->viewPath . 'create', $response);
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

    public function edit($id)
    {
        $product = $this->model->find($id);
        $productAttributeValueModel = new ProductAttributeValueModel();
        $ProductFeatureValueModel = new ProductFeatureValueModel();
        if (!$product) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'catalogs' => $this->catalog->all(),
            'product' => $product,
            'second_supplier_id' => $this->model->getSecondSupplier($product->second_supplier_id),
            'suppliers' => $this->supplier->all(),
            'attributes' => json_encode($productAttributeValueModel->where('product_id',$id)->get()->toArray()),
            'features' => json_encode($ProductFeatureValueModel->where('spu_id',$product->spu_id)->get()->toArray()),
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
        $this->validate(request(), $this->model->rules('update',$id));
        $this->model->updateProduct($id,request()->all(),request()->files);

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
        $data = $this->model->getCatalogProperty($catalog_id,$product_id);

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
        $this->model->createItem($product_id_arr);

        echo json_encode(1);
    }
}
