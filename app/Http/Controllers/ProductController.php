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
            'suppliers' => $this->supplier->all(),
            'attributes' => json_encode($productAttributeValueModel->where('product_id',$id)->get()->toArray()),
            'features' => json_encode($ProductFeatureValueModel->where('spu_id',$product->spu_id)->get()->toArray()),
            'attribute_value_id_arr' => array_column($productAttributeValueModel->where('product_id',$id)->get(['attribute_value_id'])->toArray(),'attribute_value_id'),
            'features_value_id_arr' => array_column($ProductFeatureValueModel->where('spu_id',$product->spu_id)->get(['feature_value_id'])->toArray(),'feature_value_id'),
        ];
        /*echo '<pre>';
        $a = $productAttributeValueModel->where('product_id',$id)->get(['attribute_value_id'])->toArray();
        $last_names = array_column($a, 'attribute_value_id');
        print_r($productAttributeValueModel->where('product_id',$id)->get(['attribute_value_id'])->toArray());
        exit;*/
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
        $productModel = $this->model->find($id);
        $productModel->updateProduct(request()->all(),request()->files);

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
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        foreach ($model->item as $item) {
            $item->delete();
        }
        
        $model->destroy($id);
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
        if($catalog_id==''){
            echo json_encode(0);exit;
        }
        $data = $this->model->getCatalogProperty($catalog_id);

        echo view($this->viewPath . 'ajaxset',['data' => $data]);exit;
        
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
