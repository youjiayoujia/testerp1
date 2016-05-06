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
use App\Models\Logistics\LimitsModel;
use App\Models\WrapLimitsModel;
use App\Models\ChannelModel;
use App\Models\Product\ProductVariationValueModel;
use App\Models\Product\ProductFeatureValueModel;
use Gate;

class ProductController extends Controller
{

    public function __construct(ProductModel $product,SupplierModel $supplier,CatalogModel $catalog,LimitsModel $limitsModel,WrapLimitsModel $wrapLimitsModel)
    {
        $this->model = $product;
        $this->supplier = $supplier;
        $this->catalog = $catalog;
        $this->logisticsLimit = $limitsModel;
        $this->wrapLimit = $wrapLimitsModel;
        $this->mainIndex = route('product.index');
        $this->mainTitle = '选款Model';
        $this->viewPath = 'product.';
        if (Gate::denies('check','product_admin,product_staff|show')) {
            echo "没有权限";exit;
        }

        /*if (Gate::denies('product_admin','product|show')) {
            echo "没有权限";exit;
        }*/
    }

    public function create()
    {
        if (Gate::denies('check','product_admin,product_staff|add')) {
            echo "没有权限";exit;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'catalogs' => $this->catalog->all(),
            'suppliers' => $this->supplier->all(),
            'wrapLimit' => $this->wrapLimit->all(),
            'logisticsLimit' => $this->logisticsLimit->all(),
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
        if (Gate::denies('check','product_admin,product_staff|add')) {
            echo "没有权限";exit;
        }
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
        if (Gate::denies('check','product_admin,product_staff|edit')) {
            echo "没有权限";exit;
        }
        $variation_value_id_arr = [];
        $features_value_id_arr  = [];
        $features_input = [];
        $product = $this->model->find($id);     
        if (!$product) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        //已选中的variation的id号集合     
        foreach($product->variationValues->toArray() as $key=>$arr){
            if($arr['pivot']['created_at']==$arr['pivot']['updated_at']){
                $variation_value_id_arr[$key] = $arr['pivot']['variation_value_id'];
            }   
        }
        //已选中的feature的id集合
        foreach($product->featureValues->toArray() as $key=>$arr){
            if($arr['pivot']['created_at']==$arr['pivot']['updated_at']){
                $features_value_id_arr[$key] = $arr['pivot']['feature_value_id'];
            }    
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'catalogs' => $this->catalog->all(),
            'product' => $product,
            'suppliers' => $this->supplier->all(),
            'features_input' => array_values($product->featureTextValues->where('feature_value_id',0)->toArray()),
            'variation_value_id_arr' => $variation_value_id_arr,
            'features_value_id_arr' => $features_value_id_arr,
            'wrapLimit' => $this->wrapLimit->all(),
            'logisticsLimit' => $this->logisticsLimit->all(),
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
        if (Gate::denies('check','product_admin,product_staff|edit')) {
            echo "没有权限";exit;
        }
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
        if (Gate::denies('check','product_admin,product_staff|delete')) {
            echo "没有权限";exit;
        }
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

    /**
     * 产品审核
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function choseShop()
    {
        $product_ids = request()->input('product_ids');
        $product_id_arr = explode(',', $product_ids);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->model->whereIn('id',$product_id_arr)->get()->toArray(),
            'channels' => ChannelModel::all(),
        ];

        return view($this->viewPath . 'chosechannel', $response);

    }

    

}
