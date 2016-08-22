<?php

namespace App\Console\Commands;

use App\Models\Publish\Smt\smtUserSaleCode;
use Channel;
use Illuminate\Console\Command;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Smt\smtProductSku;
use App\Models\Publish\Smt\smtProductDetail;
use App\Models\Publish\Smt\smtProductList;
use App\Models\ChannelModel;
class GetAliexpressProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smtProduct:do {accountID}';
    //protected $signature = 'smtProduct:do';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $account = AccountModel::find($this->argument('accountID'));
        $flag = true;
        $start = microtime(true);
        $currentPage = 1;
        $productDetail = array();
        $product = array();
        $productSKU = array();

        $channel = Channel::driver($account->channel->driver, $account->api_config);

            $productList = $channel->getOnlineProduct($currentPage, 20);

            if(array_key_exists('success', $productList) && $productList['success'] ){
                if($productList['aeopAEProductDisplayDTOList']){
                    foreach($productList['aeopAEProductDisplayDTOList'] as $productItem) {
                        $productInfo = $channel->findAeProductById($productItem['productId']);
                        dd($productInfo);
                        $product['productId'] = $productItem['productId'];
                        $product['token_id'] = $account->id;
                        $product['subject'] = array_key_exists('subject',$productInfo) ? $productInfo['subject'] : '';
                        $product['productPrice'] = $productInfo['productPrice'];
                        $product['productStatusType'] = $productInfo['productStatusType'];
                        $product['ownerMemberId'] = $productInfo['ownerMemberId'];
                        $product['ownerMemberSeq'] = $productInfo['ownerMemberSeq'];
                        $product['wsOfflineDate'] =  $this->parseDateString($productInfo['wsOfflineDate']);
                        $product['wsDisplay'] = array_key_exists('wsDisplay',$productInfo) ? $productInfo['wsDisplay'] : '';
                        $product['groupId'] = array_key_exists('groupId', $productInfo) ? $productInfo['groupId'] : '';
                        $product['categoryId'] = $productInfo['categoryId'];
                        $product['packageLength'] = $productInfo['packageLength'];
                        $product['packageWidth'] = $productInfo['packageWidth'];
                        $product['packageHeight'] = $productInfo['packageHeight'];
                        $product['grossWeight'] = $productInfo['grossWeight'];
                        $product['deliveryTime'] = $productInfo['deliveryTime'];
                        $product['wsValidNum'] = $productInfo['wsValidNum'];
                        $product['productMinPrice'] = $productItem['productMinPrice'];
                        $product['productMaxPrice'] = $productItem['productMaxPrice'];
                        $product['gmtCreate'] = $this->parseDateString($productItem['gmtCreate']);
                        $product['gmtModified'] = $this->parseDateString($productItem['gmtModified']);
                        $product['multiattribute'] = count($productInfo['aeopAeProductSKUs']) > 1 ? 1 : 0;
                        $product['product_url'] = 'http://www.aliexpress.com/item/-/'.$productItem['productId'].'.html';
                        $tempSKU = array_shift($productInfo['aeopAeProductSKUs']);
                        $user_id = '';
                        $sale_prefix = $this->get_skucode_prefix($tempSKU['skuCode']);
                        if($sale_prefix){
                            $userInfo = smtUserSaleCode::where('sale_code',$sale_prefix)->first();
                            if($userInfo){
                                $user_id = $userInfo->user_id;
                            }
                        }
                        $product['user_id'] = $user_id;
                        $res = smtProductList::where('productId', $productItem['productId'])->first();
                        if ($res) {
                            smtProductList::where('productId', $productItem['productId'])->update($product);
                        } else {
                            smtProductList::create($product);
                        }
    
                        $productDetail['productId'] = $productItem['productId'];
                        $productDetail['aeopAeProductPropertys'] = serialize($productInfo['aeopAeProductPropertys']);
                        $productDetail['imageURLs'] = $productInfo['imageURLs'];
                        $productDetail['detail'] = array_key_exists('detail', $productInfo) ? $productInfo['detail'] : '';
                        $productDetail['productUnit'] = $productInfo['productUnit'];
                        $productDetail['isImageDynamic'] = $productInfo['isImageDynamic'] ? 1 : 0;
                        $productDetail['lotNum'] = $productInfo['lotNum'];
                        $productDetail['bulkOrder'] = array_key_exists('bulkOrder', $productInfo) ? $productInfo['bulkOrder'] : '';
                        $productDetail['packageType'] = $productInfo['packageType'];
                        $productDetail['isPackSell'] = $productInfo['isPackSell'] ? 1 : 0;
                        $productDetail['promiseTemplateId'] = $productInfo['promiseTemplateId'];
                        $productDetail['freightTemplateId'] = $productInfo['freightTemplateId'];
                        $productDetail['src'] =  array_key_exists('src', $productInfo) ? $productInfo['src'] : '';
                        $productDetail['bulkDiscount'] = array_key_exists('bulkDiscount', $productInfo) ? $productInfo['bulkDiscount'] : '';
                        $detail = smtProductDetail::where('productId', $productItem['productId'])->first();
                        if ($detail) {
                            smtProductDetail::where('productId', $productItem['productId'])->update($productDetail);
                        } else {
                            smtProductDetail::create($productDetail);
                        }
    
                        foreach ($productInfo['aeopAeProductSKUs'] as $skuItem) {
                            $skuInfo = smtProductSku::where(['smtSkuCode' => $skuItem['skuCode'], 'productId' => $productItem['productId']])->first();
                            $skuData = array();
                            $skuData['aeopSKUProperty'] = serialize($skuItem['aeopSKUProperty']);
                            $skuCode = $this->getSkuCode($skuItem['skuCode']);
                            $skuData['skuCode'] = $skuCode;
                            $skuData['skuMark'] = $productItem['productId'].':'.$skuCode;
                            $skuData['smtSkuCode'] = $skuItem['skuCode'];
                            $skuData['skuPrice'] = $skuItem['skuPrice'];
                            $skuData['ipmSkuStock'] = $skuItem['ipmSkuStock'];
                            $skuData['productId'] = $productItem['productId'];
                            $skuData['sku_active_id'] = $skuItem['id'];
                            $aeopSKUProperty = array_shift($skuItem['aeopSKUProperty']);
                            $skuData['propertyValueId'] = $aeopSKUProperty['propertyValueId'];
                            $skuData['skuPropertyId'] = $aeopSKUProperty['skuPropertyId'];
                            $skuData['propertyValueDefinitionName'] = array_key_exists('propertyValueDefinitionName',$aeopSKUProperty) ? $aeopSKUProperty['propertyValueDefinitionName'] : '';
                            $skuData['synchronizationTime'] = date('Y:m:d H:i:s',time());
                            if ($skuInfo) {
                                smtProductSku::where(['smtSkuCode' => $skuItem['skuCode'], 'productId' => $productItem['productId']])->update($skuData);
                            } else {
                                smtProductSku::create($skuData);
                            }
                        }
                    }
                    $currentPage++;
                }else{
                    $flag = false;
                }
            }else{  
                echo  $productList['error_code'].':'.$productList['error_message'];
                $flag = false;
            }        

        $end = microtime(true);
        echo 'Running time ' . round($end - $start, 3) . ' seconds';

    }

    /**
     * 解析sku
     * @param $skuCode
     * @return mixed
     */
    public function getSkuCode($skuCode){
        $temp = explode('*',$skuCode);
        $sku = '';
        if(count($temp) == 2) {
            $temp1 = explode('#', $temp[1]);
            $sku = $temp1[0];
        }else{
            $temp1 = explode('#', $temp[0]);
            $sku = $temp1[0];
        }
        return $sku;
    }

    /**
     * 获取速卖通销售前缀
     * @param $sku
     * @return string
     */
    public function get_skucode_prefix($sku)
    {
        $len    = 0;
        $prefix = '';
        if (($len = stripos($sku, '*')) > 0) {
            $prefix = substr($sku, 0, $len);
        }
        return strtoupper(trim($prefix));
    }

    public function parseDateString($string){
        $data = "";
        if($string){
            $dateString = substr($string,0,14);
            $year = substr($dateString,0,4);
            $month = substr($dateString,4,2);
            $day = substr($dateString,6,2);
            $hour = substr($dateString,8,2);
            $minute = substr($dateString,10,2);
            $second = substr($dateString,12,2);
            $date = $year."-" .$month. "-" . $day ." " . $hour . ":" .$minute. ":" .$second;
        }
        return $date;
    }

}
