<?php
/**
 * 产品管理控制器
 * @author: youjia
 * Date: 2016-1-4 10:46:32
 */
namespace App\Http\Controllers;
use App\Models\ItemModel;
use App\Models\ProductModel;
use App\Models\CatalogModel;
use App\Models\Product\SupplierModel;
use App\Models\Logistics\LimitsModel;
use App\Models\WrapLimitsModel;
use App\Models\ChannelModel;
use App\Models\UserModel;
use App\Models\WarehouseModel;
use App\Models\Product\ProductVariationValueModel;
use App\Models\Product\ProductFeatureValueModel;
use App\Models\Product\RequireModel;
use App\Models\Logistics\CatalogModel as LogisticsCatalog;
use App\Models\LogisticsModel;
use App\Models\Logistics\ZoneModel;
use App\Models\Catalog\CatalogChannelsModel;
use App\Models\CurrencyModel;
use Gate;
use App\Models\PaypalRatesModel;
class ProductController extends Controller
{
    public function __construct(ProductModel $product,SupplierModel $supplier,CatalogModel $catalog,LimitsModel $limitsModel,WrapLimitsModel $wrapLimitsModel,WarehouseModel $warehouse)
    {
        $this->model = $product;
        $this->supplier = $supplier;
        $this->catalog = $catalog;
        $this->logisticsLimit = $limitsModel;
        $this->warehouse = $warehouse;
        $this->wrapLimit = $wrapLimitsModel;
        $this->mainIndex = route('product.index');
        $this->mainTitle = '选款Model';
        $this->viewPath = 'product.';
        /*if (Gate::denies('check','product_admin,product_staff|show')) {
            echo "没有权限";exit;
        }*/
        /*if (Gate::denies('product_admin','product|show')) {
            echo "没有权限";exit;
        }*/
    }
    public function create()
    {
        /*if (Gate::denies('check','product_admin,product_staff|add')) {
            echo "没有权限";exit;
        }*/
        $require_id = request()->input('id');
        $requireModel = RequireModel::find($require_id);
        $data = $this->catalog->getCatalogProperty($requireModel->catalog_id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'catalogs' => $this->catalog->find($requireModel->catalog_id),
            'suppliers' => $this->supplier->all(),
            'wrapLimit' => $this->wrapLimit->all(),
            'data' =>$data,
            'require_id' =>$require_id,
            'users' => UserModel::all(),
            'warehouses' => $this->warehouse->where('type','local')->get(),
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
        /*if (Gate::denies('check','product_admin,product_staff|add')) {
            echo "没有权限";exit;
        }*/
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        if(!array_key_exists('modelSet',request()->all())){
            return redirect(route('product.create'))->with('alert', $this->alert('danger', '请选择model.'));
        }

        $this->model->createProduct(request()->all(),request()->files);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '添加成功.'));
    }
    /**
     * 产品编辑页面
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit($id)
    {
        /*if (Gate::denies('check','product_admin,product_staff|edit')) {
            echo "没有权限";exit;
        }*/
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
        $logisticsLimit_arr = [];
        foreach($product->logisticsLimit->toArray() as $key=>$arr){
            $logisticsLimit_arr[$key] = $arr['pivot']['logistics_limits_id'];
        }
        $wrapLimit_arr = [];
        foreach($product->wrapLimit->toArray() as $key=>$arr){
            $wrapLimit_arr[$key] = $arr['pivot']['wrap_limits_id'];
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'catalogs' => $this->catalog->all(),
            'product' => $product,
            'suppliers' => $this->supplier->all(),
            'features_input' => array_values($product->featureTextValues->where('feature_value_id',0)->toArray()),
            'variation_value_id_arr' => $variation_value_id_arr,
            'features_value_id_arr' => $features_value_id_arr,
            'warehouses' => $this->warehouse->where('type','local')->get(),
            'wrapLimit' => $this->wrapLimit->all(),
            'users' => UserModel::all(),
            'logisticsLimit' => $this->logisticsLimit->all(),
            'wrapLimit_arr' => $wrapLimit_arr,
            'logisticsLimit_arr' => $logisticsLimit_arr,
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
        /*if (Gate::denies('check','product_admin,product_staff|edit')) {
            echo "没有权限";exit;
        }*/
        request()->flash();
        $this->validate(request(), $this->model->rules('update',$id));
        $productModel = $this->model->find($id);
        $productModel->updateProduct(request()->all(),request()->files);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '更新成功.'));
    }
    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        /*if (Gate::denies('check','product_admin,product_staff|delete')) {
            echo "没有权限";exit;
        }*/
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $model->destoryProduct();
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '删除成功.'));
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
    /**
     * 小语言编辑
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productMultiEdit()
    {

        $data = request()->all();
        $language = config('product.multi_language');
        $model = $this->model->find($data['id']);
        $default = $model->productMultiOption->where("channel_id",ChannelModel::all()->first()->id)->first()->toArray();
        //print_r($default);exit;
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' =>$this->model->find($data['id']),
            'languages' => config('product.multi_language'),
            'channels' => ChannelModel::all(),
            'id' => $data['id'],
            'default' =>$default,
        ];
        return view($this->viewPath . 'language', $response);
    }
    /**
     * 小语言更新
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productMultiUpdate()
    {
        $data = request()->all();
        //echo '<pre>';
        //print_r($data);exit;
        $productModel = $this->model->find($data['product_id']);
        $productModel->updateMulti($data);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '编辑成功.'));
    }
    /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $logisticsLimit_arr = [];
        foreach($model->logisticsLimit->toArray() as $key=>$arr){
            $logisticsLimit_arr[$key] = $arr['name'];
        }

        $wrapLimit_arr = [];
        foreach($model->wrapLimit->toArray() as $key=>$arr){
            $wrapLimit_arr[$key] = $arr['name'];
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'warehouse' => $this->warehouse->find($model->warehouse_id),
            'logisticsLimit_arr' => $logisticsLimit_arr,
            'wrapLimit_arr' => $wrapLimit_arr,
        ];
        return view($this->viewPath . 'show', $response);
    }
    /**
     * 批量更新界面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productBatchEdit()
    {
        $product_ids = request()->input("product_ids");
        $arr = explode(',', $product_ids);
        $param = request()->input('param');

        $products = $this->model->whereIn("id",$arr)->get();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'products' => $products,
            'product_ids'=>$product_ids,
            'param'  =>$param,
            'wrapLimit' => $this->wrapLimit->all(),
        ];
        return view($this->viewPath . 'batchEdit', $response);
    }
    /**
     * 批量更新
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productBatchUpdate()
    {
        $product_ids = request()->input("product_ids");
        $arr = explode(',', $product_ids);
        $products = $this->model->whereIn("id",$arr)->get();
        $data = request()->all();
        $data['package_limit'] = empty($data['package_limit_arr']) ? '':implode(',', $data['package_limit_arr']);
        foreach($products as $productModel){
            $productModel->update($data);
        }
        return redirect($this->mainIndex);
    }
    /**
     * 批量更新
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productInfo()
    {
        $channel_id = request()->input("channel_id");
        $language = request()->input("language");
        $product_id = request()->input("product_id");
        $model = $this->model->find($product_id);
        $info = $model->productMultiOption->where("channel_id",(int)$channel_id)->first()->toArray();
        $result['name'] = $info[$language."_name"];
        $result['description'] = $info[$language."_description"];
        $result['keywords'] = $info[$language."_keywords"];
        return $result;
    }
    /**
     * 批量更新
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePurchaseAdmin($product_id)
    {
        $user_name = request()->input('manual_name');
        $user_id = request()->input('purchase_adminer');
        $model = $this->model->find($product_id);
        if($user_id){
            $model->update(['purchase_adminer'=>$user_id]);
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '采购员变更成功.'));
        }else{
            $userModel = UserModel::where('name',$user_name)->first();
            if($userModel){
                $model->update(['purchase_adminer'=>$userModel->id]);
                return redirect($this->mainIndex)->with('alert', $this->alert('success', '采购员变更成功.'));
            }else{
                return redirect($this->mainIndex)->with('alert', $this->alert('danger','该用户不存在.'));
            }
        }

    }
    /**
     * 获取供应商信息
     */
    public function ajaxSupplierUser()
    {
        $product_id = request()->input('product_id');
        $model = $this->model->find($product_id);
        $user_array = ProductModel::where('supplier_id',$model->supplier_id)->distinct()->get();
        $in = [];
        foreach ($user_array as $array) {
            $in[] = $array->purchase_adminer;
        }

        if(request()->ajax()) {
            $user = trim(request()->input('user'));
            $buf = UserModel::where('name', 'like', '%'.$user.'%')->whereIn('id',$in)->get();
            $total = $buf->count();
            $arr = [];
            foreach($buf as $key => $value) {
                $arr[$key]['id'] = $value->id;
                $arr[$key]['text'] = $value->name;
            }
            if($total)
                return json_encode(['results' => $arr, 'total' => $total]);
            else
                return json_encode(false);
        }
        return json_encode(false);
    }

    /**
     * 产品价格计算
     * @param ZoneModel $zone
     * @param ItemModel $items_obj
     */
    public function ajaxReturnPrice(ZoneModel $zone,ItemModel  $items_obj,PaypalRatesModel $rates_obj){
        $return_price_array = [];
        $shipment_fee = false;
        $form_ary =  request()->input();
        //dd($form_ary);exit;
        //获取运费
        if(isset($form_ary['zone_id'])){
            $shipment_fee = $zone->getShipmentFee($form_ary['zone_id'],$form_ary['product_weight']);   //人民币运费
        }
        if($shipment_fee != false){
            //获取售价
            $product_obj = $items_obj->find($form_ary['product_id']);
            $channels_rate = $product_obj->catalog->channels;
            $rates = $rates_obj->find(1); //paypal固定税率
            $USD_obj = CurrencyModel::where('code','=','RMB')->first(); //美元->人民币 汇率
            foreach ($channels_rate as $item_channel){

                if($form_ary['channel_id'] != 'none'){ //如果指定渠道
                    if($item_channel->name != $form_ary['channel_id']) {
                        continue;
                    }
                }
                /**
                 * 亚马逊平台加临界值判断规则
                 * 小于临界值   售价=（采购成本+平台费用+物流成本）（1-利润率）
                 * 大于临界值   售价=（采购成本+物流成本+PP固定费用）/（1-利润率-分类费率-PP成交费率）
                 *
                 */
                $channel_fee = 0; //初始化 平台费
                switch ($item_channel->name){
                    case '亚马逊美国':
                        //AMZ美国站平台费小于1美元，按1美元计算(珠宝及手表分类下，该条件用2美元计算)
                        $channel_fee = $product_obj->purchase_price * $item_channel->pivot->rate / 100; // 平台费USD
                        if($channel_fee < 1){
                            $channel_fee = 1;
                            if($this->IsWatchAndJewelry($product_obj->cname)){
                                $channel_fee = 2;
                            }
                            //售价=（采购成本+平台费用+物流成本）（1-利润率）
                            $sale_price_big =  ($product_obj->purchase_price + $shipment_fee + $channel_fee)  / (1 - $form_ary['profit_id'] / 100) / $USD_obj->rate;
                            $sale_price_small = 0;
                        }else{
                            $sale_price_big =  ($product_obj->purchase_price + $shipment_fee) / (1 - $form_ary['profit_id'] / 100 - $item_channel->pivot->rate /100 ) /$USD_obj->rate;
                            $sale_price_small = 0;
                        }
                        break;
                    case '亚马逊英国':
                        //AMZ英国站平台费小于0.5英镑，按0.5英镑计算(珠宝及手表分类下，该条件用1.25英镑计算)
                        $channel_fee = $product_obj->purchase_price * $item_channel->pivot->rate / 100; // 平台费USD
                        $GBP_obj = CurrencyModel::where('code','=','GBP')->first(); //美元英镑汇率
                        $channel_fee = $GBP_obj->rate * $channel_fee; //平台费 GBP
                        if($channel_fee < 0.5){
                            $channel_fee = 0.5;
                            if($this->IsWatchAndJewelry($product_obj->cname)){
                                $channel_fee = 1.25;
                            }
                            $channel_fee = $channel_fee / $GBP_obj->rate; // 平台费USD
                            //售价=（采购成本+平台费用+物流成本）（1-利润率）
                            $sale_price_big =  ($product_obj->purchase_price + $shipment_fee + $channel_fee)  / (1 - $form_ary['profit_id'] / 100) / $USD_obj->rate;
                            $sale_price_small = 0;

                        }else{
                            $sale_price_big =  ($product_obj->purchase_price + $shipment_fee) / (1 - $form_ary['profit_id'] / 100 - $item_channel->pivot->rate /100 ) /$USD_obj->rate;
                            $sale_price_small = 0;

                        }

                        break;
                    case '亚马逊欧洲':
                        $channel_fee = $product_obj->purchase_price * $item_channel->pivot->rate / 100; // 平台费USD
                        $EUR_obj = CurrencyModel::where('code','=','EUR')->first(); //美元欧元汇率
                        $channel_fee = $EUR_obj->rate * $channel_fee; //平台费 EUR
                        if($channel_fee < 0.5){
                            $channel_fee = 0.5;
                            if($this->IsWatchAndJewelry($product_obj->cname)){
                                $channel_fee = 1.5;
                            }
                            //兑换美元
                            $channel_fee = $channel_fee / $EUR_obj->rate; // 平台费USD
                            //售价=（采购成本+平台费用+物流成本）（1-利润率）
                            $sale_price_big =  ($product_obj->purchase_price + $shipment_fee + $channel_fee)  / (1 - $form_ary['profit_id'] / 100) / $USD_obj->rate;
                            $sale_price_small = 0;

                        }else{
                            $sale_price_big =  ($product_obj->purchase_price + $shipment_fee) / (1 - $form_ary['profit_id'] / 100 - $item_channel->pivot->rate /100 ) /$USD_obj->rate;
                            $sale_price_small = 0;
                        }
                        break;
                    case '亚马逊日本':
                        //AMZ日本站平台费小于30日元，按30日元计算(珠宝及手表分类下，该条件用50日元计算)
                        $channel_fee = $product_obj->purchase_price * $item_channel->pivot->rate / 100; // 平台费USD
                        $JPY_obj = CurrencyModel::where('code','=','JPY')->first(); //美元日元汇率
                        $channel_fee = $JPY_obj->rate * $channel_fee; //平台费 JPY
                        if($channel_fee < 30){
                            $channel_fee = 30;
                            if($this->IsWatchAndJewelry($product_obj->cname)){
                                $channel_fee = 50;
                            }
                            //兑换美元
                            $channel_fee = $channel_fee / $JPY_obj->rate; // 平台费USD
                            $sale_price_big =  ($product_obj->purchase_price + $shipment_fee + $channel_fee)  / (1 - $form_ary['profit_id'] / 100) / $USD_obj->rate;
                            $sale_price_small = 0;

                        }else{
                            $sale_price_big =  ($product_obj->purchase_price + $shipment_fee) / (1 - $form_ary['profit_id'] / 100 - $item_channel->pivot->rate /100 ) /$USD_obj->rate;
                            $sale_price_small = 0;
                        }
                        break;
                    case 'eBay欧洲':
                    case 'eBay澳洲':
                    case 'eBay美国':
                    case 'eBay英国':

                    /**
                     * EBAY售价=（采购成本+物流成本+PP固定费用0.3美金）/（1-利润率-分类费率-小PP成交费率3%）
                     * 如果是大PP计算，则小PP成交费率为0
                     */
                        $sale_price_big   = ($product_obj->purchase_price + $shipment_fee + $rates->fixed_fee_big) / (1 - $form_ary['profit_id'] / 100 - $item_channel->pivot->rate /100 ) / $USD_obj->rate;
                        $sale_price_small =  ($product_obj->purchase_price + $shipment_fee + $rates->fixed_fee_big) / (1 - $form_ary['profit_id'] / 100 - $item_channel->pivot->rate /100 - $rates->transactions_fee_small/100) / $USD_obj->rate;
                        break;
                    default:
                        //其他渠道统一计算
                        //售价=（采购成本+物流成本+PP固定费用）/（1-利润率-分类费率-小PP成交费率）
                        $sale_price_big =  ($product_obj->purchase_price + $shipment_fee) / (1 - $form_ary['profit_id'] / 100 - $item_channel->pivot->rate /100 ) /$USD_obj->rate;
                        $sale_price_small = 0;
                        break;

                }

                $return_price_array[] = [
                    'channel_name' => $item_channel->name,
                    'sale_price_big' => number_format($sale_price_big,2,'.',''),
                    'sale_price_small' => number_format($sale_price_small,2,'.',''),
                ];
            }
        }else{
            print_r(json_encode(['status' => -2,'error_msg' => '计算运费参数错误，请检查。']));
            return;
        }

        if($return_price_array){
            print_r(json_encode(['status' =>1, 'data' => $return_price_array]));

        }else{
            print_r(json_encode(['status' => -1]));

        }

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
            'mixedSearchFields' => $this->model->mixed_search,
            'Compute_logistics_catalog'=> LogisticsCatalog::all(),
            'Compute_logistics'=> LogisticsModel::all(),
            'Compute_channels' => ChannelModel::all(),

        ];
        return view($this->viewPath . 'index', $response);
    }
    public function ajaxReturnLogistics(){
        if(request()->ajax()){
            $id = request()->input('id');
            switch (request()->input('type')){
                case 'catalog':
                    $data = LogisticsModel::where('logistics_catalog_id','=',$id)->get()->toJson();
                    break;
                case 'logistics':
                    $data = ZoneModel::where('logistics_id','=',$id)->get()->toJson();
                    break;
                default :
                    return;
            }
            return $data;
        }else{
            return config('ajax.fail');
        }
    }
    /**
     * ajax获取物流分类
     */
    public function ajaxReutrnCatalogs()
    {
        if(request()->ajax()) {
            $name = trim(request()->input('name'));
            $buf = LogisticsCatalog::where('name', 'like', '%'.$name.'%')->get();
            $total = $buf->count();
            $arr = [];
            foreach($buf as $key => $value) {
                $arr[$key]['id'] = $value->id;
                $arr[$key]['text'] = $value->name;
            }
            if($total)
                return json_encode(['results' => $arr, 'total' => $total]);
            else
                return json_encode(false);
        }
        return json_encode(false);
    }


    /**
     * 检查产品品类是否未手表和珠宝
     * @param bool $name
     * @return bool
     */
    public function IsWatchAndJewelry($name=false){

        $effective = [
                      '松珠','裸钻','精细珠宝','男士珠宝','首饰盒组织者',
                      '时尚珠宝','手表','批发地段','复古与古董珠宝','儿童首饰',
                      '订婚和婚礼','珠宝及手表--其他','手工制作，工匠珠宝','珠宝设计与维修'
                     ];
            if(in_array($name,$effective)){
                return true;
            }else{
                return false;
            }


    }

}