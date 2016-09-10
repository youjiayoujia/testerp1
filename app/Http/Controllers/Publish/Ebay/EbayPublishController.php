<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-05
 * Time: 14:32
 */
namespace App\Http\Controllers\Publish\Ebay;

use Channel;
use App\Http\Controllers\Controller;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Ebay\EbayPublishProductModel;
use App\Models\Publish\Ebay\EbayPublishProductDetailModel;
use App\Models\PaypalsModel;
use App\Models\Publish\Ebay\EbaySellerCodeModel;
use App\Models\Publish\Ebay\EbaySiteModel;
use App\Models\Publish\Ebay\EbayCategoryModel;
use App\Models\Publish\Ebay\EbaySpecificsModel;
use App\Models\Publish\Ebay\EbayConditionModel;
use App\Models\Publish\Ebay\EbayShippingModel;


class EbayPublishController extends Controller
{
    public function __construct(EbayPublishProductModel $ebayProduct,
                                EbayPublishProductDetailModel $ebayProductDetail,
                                EbaySellerCodeModel $sellerCode,
                                EbaySiteModel $ebaySite,
                                PaypalsModel $payPal,
                                EbaySpecificsModel $ebaySpecifics,
                                EbayConditionModel $ebayCondition,
                                EbayShippingModel $ebayShipping)
    {
        $this->model = $ebayProduct;
        $this->mainIndex = route('ebayPublish.index');
        $this->mainTitle = 'Ebay草稿刊登';
        $this->viewPath = 'publish.ebay.publish.';
        $this->modelDetail = $ebayProductDetail;
        $this->sellerCode = $sellerCode;
        $this->ebaySite = $ebaySite;
        $this->payPal = $payPal;
        $this->ebaySpecifics = $ebaySpecifics;
        $this->ebayCondition = $ebayCondition;
        $this->ebayShipping = $ebayShipping;
    }






    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'site' =>$this->ebaySite->getSite('site_id'),
        ];
        return view($this->viewPath . 'create', $response);
    }






    public function ajaxInitSite(){
        $where =[];
        $where['site_id'] = request()->input('site');
        $return = [];
        $shipping = $this->ebayShipping->where($where)->get();
        $ship_text ='<option value="">==请选择==</option>';
        $international_text ='<option value="">==请选择==</option>';
        foreach($shipping as $ship){
            if($ship->valid_for_selling_flow==1){
                $days = '';
                if(!($ship->shipping_time_min ==0&&$ship->shipping_time_max==0)){
                    $days ='('. $ship->shipping_time_min.'-'.$ship->shipping_time_max.')';
                }
                if($ship->international_service==2){
                    $ship_text .='<option value="'.$ship->shipping_service_id.'">'.$ship->description.$days.'</option>';
                }
                if($ship->international_service==1){
                    $international_text .='<option value="'.$ship->shipping_service_id.'">'.$ship->description.$days.'</option>';
                }
            }
        }
        $return['ship_text'] = $ship_text;
        $return['international_text'] = $international_text;

        $siteInfo = $this->ebaySite->where($where)->first();
        $returns_with_in = '<option value="">==请选择==</option>';
        $shipping_costpaid_by = '<option value="">==请选择==</option>';
        $refund ='<option value="">==请选择==</option>';
        if(!empty($siteInfo)){
            $returns_with_in_data = json_decode($siteInfo->returns_with_in,true);
            if(!empty($returns_with_in_data)){
                foreach($returns_with_in_data as $v){
                    $returns_with_in .='<option value="'.$v.'">'.$v.'</option>';
                }
            }
            $shipping_costpaid_by_data = json_decode($siteInfo->shipping_costpaid_by,true);
            if(!empty($shipping_costpaid_by_data)){
                foreach($shipping_costpaid_by_data as $v){
                    $shipping_costpaid_by .='<option value="'.$v.'">'.$v.'</option>';
                }
            }

            $refund_data = json_decode($siteInfo->refund,true);
            if(!empty($refund_data)){
                foreach($refund_data as $v){
                    $refund .='<option value="'.$v.'">'.$v.'</option>';
                }
            }
        }
        $return['returns_with_in'] = $returns_with_in;
        $return['shipping_costpaid_by'] = $shipping_costpaid_by;
        $return['refund'] = $refund;
        echo json_encode($return);
        die;

    }

    public function ajaxInitCategory(){
        $where =[];
        $where['site'] = request()->input('site');
        $where['category_level'] =  request()->input('level') +1;
        $category_parent_id = request()->input('category_parent_id');

        if(!empty($category_parent_id)){
            $where['category_parent_id']  = $category_parent_id;
        }
        $category = EbayCategoryModel::where($where)->get(['category_id','category_level','leaf_category','category_name'])->toArray();
        echo json_encode($category);
    }



    public function ajaxInitErpData(){
        $return = [];
        $sku = request()->input('sku');
        if(empty($sku)){
            echo false;
            die;
        }
        $type =  request()->input('type');
        $type = explode('+',$type);

        $skuNew = explode('*',$sku);
        $sku = count($skuNew)>1?$skuNew[1]:$skuNew[0];
        $skuFore = count($skuNew)>1?$skuNew[0]:'';
        $skuNew = explode('(',$sku);
        $sku = $skuNew[0];
        $skuRear = count($skuNew)>1?$skuNew[1]:'';

        //$skuNew = $this->handleSku($sku);
        foreach($type as $v){
            if ($v == 'sku') {
                $test = ['AAA', 'BBB', 'CCC', 'DDD', 'EEE', 'FFF'];
                foreach ($test as $te) {
                    $erpSku = $te;
                    if (!empty($skuFore)) {
                        $erpSku = $skuFore . '*' . $erpSku;
                    }
                    if (!empty($skuRear)) {
                        $erpSku = $erpSku.'('.$skuRear;
                    }
                    $return['sku'][] = $erpSku;
                }
            }
            if($v=='picture'){
                $return['picture']=['https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=51553588,3796425299&fm=58',
                    'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=51553588,3796425299&fm=58',
                    'https://ss2.baidu.com/6ONYsjip0QIZ8tyhnq/it/u=2307604988,1294428785&fm=58',
                    'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=51553588,3796425299&fm=58',
                    'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=51553588,3796425299&fm=58'];

            }
        }

        echo json_encode($return);
        die;

    }

    public function ajaxInitSpecifics(){
        $site = request()->input('site');
        $category_id =  request()->input('category_id');
        $return = [];
        $result = $this->ebaySpecifics->getSiteCategorySpecifics($category_id,$site);
        $i = 0;
        if($result){
            foreach ($result as $re) {
                $return[$i]['name'] = $re['name'];
                if($re['min_values']>0){
                    $return[$i]['must'] = true;
                }else{
                    $return[$i]['must'] = false;
                }
                $text = '<option value="">==请选择==</option>';
                $specific_values = json_decode($re['specific_values'], true);
                if (!empty($specific_values)) {
                    foreach ($specific_values as $s_v) {
                        $text .= '<option value="' . $s_v . '">' . $s_v . '</option>';
                    }
                }
                $return[$i]['text'] = $text;
                $i++;

            }
            echo json_encode($return);
            die;
        }else{
            echo false;
            die;
        }

    }

    public function ajaxInitCondition(){
        $site = request()->input('site');
        $category_id =  request()->input('category_id');
        $return = [];
        $result = $this->ebayCondition->getSiteCategoryCondition($category_id,$site);
        if($result){
            $text = '<option value="">==请选择==</option>';
            foreach($result as $key=> $re){
                if($key==0){
                    $return['is_variations'] = $re['is_variations'];
                    $return['is_upc'] = $re['is_upc'];
                    $return['is_ean'] = $re['is_ean'];
                    $return['is_isbn'] = $re['is_isbn'];
                }
                $text .=  '<option value="' . $re['condition_id'] . '">' . $re['condition_name'] . '</option>';
            }
            $return['text'] = $text;
            echo json_encode($return);
            die;

        }else{
            echo false;
            die;
        }
    }

    /**
     * @param $sku
     * $return = [
     *      'sku'
     *      'prefix'
     *      'num'
     *
     * ]
     */
    public function handleSku($sku){
        $return = [];
        $skuMid = explode('*',$sku);
        $return['sku'] = count($skuMid)>1?$skuMid[1]:$skuMid[0];
        $return['prefix'] = count($skuMid)>1?$skuMid[0]:"";
        $return['num'] = 1;
    }








    public function store(){
        var_dump($_POST);
        exit;
    }

















}