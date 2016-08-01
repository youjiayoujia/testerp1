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
        var_dump($result);
    }
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



}

