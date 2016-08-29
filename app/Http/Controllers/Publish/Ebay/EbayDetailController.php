<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-01
 * Time: 13:52
 */
namespace App\Http\Controllers\Publish\Ebay;
use Channel;
use App\Models\Channel\AccountModel;
use App\Http\Controllers\Controller;
use App\Models\ChannelModel;
use App\Models\Publish\Ebay\EbaySiteModel;
use App\Models\Publish\Ebay\EbayShippingModel;
use App\Models\Publish\Ebay\EbayCategoryModel;


class EbayDetailController extends Controller
{
    public function __construct(EbaySiteModel $ebaySite,EbayShippingModel $ebayShipping)
    {
        $this->model = $ebaySite;
        $this->mainIndex = route('ebayDetail.index');
        $this->mainTitle = 'ebay站点信息';
        $this->viewPath = 'publish.ebay.site.';
        $this->ebayShipping = $ebayShipping;
    }

    /*
     * 获取可用站点
     */
    public function getEbaySite(){


        $accountID =9;
        $account = AccountModel::findOrFail($accountID);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->getEbaySite();
        if($result){
            foreach($result as $re){
                $siteInfo=  $this->model->where('site_id',$re['site_id'])->first();
               if(empty($siteInfo)){ //ADD
                   $this->model->create($re);
               }else{//update
                   $this->model->where('id',$siteInfo->id)->update($re);
               }
            }
        }else{

        }

    }
    /*
     * 退货政策
     */
    public function getEbayReturnPolicy(){

        $accountID =9;
        $site = 77;
        $account = AccountModel::findOrFail($accountID);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->getEbayReturnPolicy($site);
        if($result){
            $siteInfo = $this->model->where('site_id',$site)->first();
            if(!empty($siteInfo)){
                $this->model->where('site_id',$site)->update($result);

            }else{
                echo '未找到该站点信息!';
            }
        }else{

        }
    }

    /*
     * 获得对应站点的运输方式
     */
    public function getEbayShipping(){
        $accountID =9;
        $site = 3;
        $account = AccountModel::findOrFail($accountID);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->getEbayShipping($site);
        if($result){
            foreach($result as $ship){
                $ship['site_id'] = $site;
                $shipInfo = $this->ebayShipping->where('site_id',$site)->where('shipping_service_id',$ship['shipping_service_id'])->first();
                if(!empty($shipInfo)){
                    $this->ebayShipping->where('id',$shipInfo->id)->update($ship);
                }else{
                    $this->ebayShipping->create($ship);
                }
            }

            echo 'ok';
        }else{
            echo 'false';
        }
    }

    /*
     * 获取对应站点的分类
     */
    public function getEbayCategory(){
        $accountId= 378;
        $site = 0;
        $account = AccountModel::findOrFail($accountId);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $category_result = EbayCategoryModel::where(['site'=>$site,'category_level'=>1])->get();
        if(empty($category_result)){
            $result = $channel->getEbayCategoryList(1,'',$site);
            if($result){
                foreach($result as $re){
                    EbayCategoryModel::create($re);
                }
            }
        }else{
            EbayCategoryModel::where('site',$site)->where('category_level','!=',1)->delete();
            foreach($category_result as $category ){
                $result = $channel->getEbayCategoryList(6,$category->category_id,$site);
                if($result){
                    foreach($result as $re){
                        EbayCategoryModel::create($re);
                    }
                }

            }
        }

    }






}

