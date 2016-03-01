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
use App\Models\WarehouseModel;
use App\Models\Product\ProductVariationValueModel;
use App\Models\Product\ProductFeatureValueModel;

class ProductController extends Controller
{

    public function __construct(ProductModel $product,SupplierModel $supplier,CatalogModel $catalog,WarehouseModel $warehouse)
    {
        $this->model = $product;
        $this->supplier = $supplier;
        $this->catalog = $catalog;
        $this->warehouse = $warehouse;
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
            'warehouses' => $this->warehouse->where('type','本地仓库')->get(),
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

    /**
     * 产品编辑页面
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit($id)
    {
        $variation_value_id_arr = [];
        $features_value_id_arr  = [];
        $product = $this->model->find($id);     
        if (!$product) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        //已选中的variation的id号集合     
        foreach($product->ProductVariationvalue->toArray() as $key=>$arr){
            if($arr['pivot']['created_at']==$arr['pivot']['updated_at']){
                $variation_value_id_arr[$key] = $arr['pivot']['variation_value_id'];
            }   
        }
        //已选中的feature的id集合
        foreach($product->ProductManyToFeaturevalue->toArray() as $key=>$arr){
            if($arr['pivot']['created_at']==$arr['pivot']['updated_at']){
                $features_value_id_arr[$key] = $arr['pivot']['feature_value_id'];
            }
            
        }
        
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'catalogs' => $this->catalog->all(),
            'product' => $product,
            'suppliers' => $this->supplier->all(),
            'features_input' => array_values($product->productFeatureValue->where('feature_value_id',0)->toArray()),
            'variation_value_id_arr' => $variation_value_id_arr,
            'features_value_id_arr' => $features_value_id_arr,
            'warehouses' => $this->warehouse->where('type','本地仓库')->get(),
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
        $model->destoryProduct();

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
        $catalog_id = request()->input('catalog_id');
        if($catalog_id==''){
            return 0;
        }
        $data = $this->catalog->getCatalogProperty($catalog_id);

        return view($this->viewPath . 'ajaxset',['data' => $data]);

    }

    /**
     * 产品审核
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examine()
    {
        $product_ids = request()->input('product_ids');
        $product_id_arr = explode(',', $product_ids);
        //创建item
        foreach($product_id_arr as $product_id){
            $model = $this->model->find($product_id);
            $model->createItem();
        }

        return 1;
    }
}
