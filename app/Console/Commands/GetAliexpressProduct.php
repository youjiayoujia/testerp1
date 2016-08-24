<?php

namespace App\Console\Commands;

use App\Models\ErpSalesPlatform;
use App\Models\ProductModel;
use App\Models\ErpSystem;
use App\Models\Publish\Smt\smtUserSaleCode;
use App\Models\SkuPublishRecords;
use App\Modules\Channel\Adapter\AliexpressAdapter;
use Channel;
use Illuminate\Console\Command;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Smt\smtProductSku;
use App\Models\Publish\Smt\smtProductDetail;
use App\Models\Publish\Smt\smtProductList;
use App\Models\ChannelModel;
use App\Models\Logistics\ZoneModel;
use App\Models\CurrencyModel;
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
    
    private $_lowerProfitRate = 10;
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
        $_product_statues_type = array("onSelling", "offline", "auditing", "editingRequired");  //商品业务状态
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $shipArray = $this->_getSmtDefinedShipForDiscount();
        while($flag) {
            foreach($_product_statues_type as $type){
                dd($type);
                $productList = $channel->getOnlineProduct($type,$currentPage, 100);
                if (array_key_exists('success', $productList) && $productList['success']) {
                    if ($productList['aeopAEProductDisplayDTOList']) {
                        foreach ($productList['aeopAEProductDisplayDTOList'] as $productItem) {
                            $productInfo = $channel->findAeProductById($productItem['productId']);
                            dd($productInfo);
                            $product['productId'] = $productItem['productId'];
                            $product['product_url'] = 'http://www.aliexpress.com/item/-/' . $productItem['productId'] . '.html';
                            $product['token_id'] = $account->id;
                            $product['subject'] = array_key_exists('subject', $productInfo) ? $productInfo['subject'] : '';
                            $product['productPrice'] = $productInfo['productPrice'];
                            $product['productStatusType'] = $productInfo['productStatusType'];
                            $product['ownerMemberId'] = $productInfo['ownerMemberId'];
                            $product['ownerMemberSeq'] = $productInfo['ownerMemberSeq'];
                            $product['wsOfflineDate'] = $this->parseDateString($productInfo['wsOfflineDate']);
                            $product['wsDisplay'] = array_key_exists('wsDisplay', $productInfo) ? $productInfo['wsDisplay'] : '';
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
                
                            $tempSKU = array_shift($productInfo['aeopAeProductSKUs']);
                            $user_id = '';
                            //获取销售前缀
                            $sale_prefix = $this->get_skucode_prefix($tempSKU['skuCode']);
                            if ($sale_prefix) {
                                $userInfo = smtUserSaleCode::where('sale_code', $sale_prefix)->first();
                                if ($userInfo) {
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
                            $productDetail['isImageWatermark'] = array_key_exists('isImageWatermark', $productInfo) ? ($productInfo['isImageWatermark'] ? 1 : 0) : 0;
                            $productDetail['lotNum'] = $productInfo['lotNum'];
                            $productDetail['bulkOrder'] = array_key_exists('bulkOrder', $productInfo) ? $productInfo['bulkOrder'] : 0;
                            $productDetail['packageType'] = $productInfo['packageType'];
                            $productDetail['isPackSell'] = $productInfo['isPackSell'] ? 1 : 0;
                            $productDetail['promiseTemplateId'] = $productInfo['promiseTemplateId'];
                            $productDetail['freightTemplateId'] = $productInfo['freightTemplateId'];
                            $productDetail['sizechartId'] = array_key_exists('sizechartId', $productInfo) ? $productInfo['sizechartId'] : 0;
                            $productDetail['src'] = array_key_exists('src', $productInfo) ? $productInfo['src'] : '';
                            $productDetail['bulkDiscount'] = array_key_exists('bulkDiscount', $productInfo) ? $productInfo['bulkDiscount'] : 0;
                
                            $detail = smtProductDetail::where('productId', $productItem['productId'])->first();
                            if ($detail) {
                                smtProductDetail::where('productId', $productItem['productId'])->update($productDetail);
                                $localSmtSkuList = $this->getLocalSmtSkuCodeBy($productItem['productId']);
                                $onlineSmtSkuList = array();
                                foreach ($productInfo['aeopAeProductSKUs'] as $sku_list) {
                                    $onlineSmtSkuList[] = strtoupper(trim($sku_list['skuCode']));
                                }
                                //本地存在，线上已被删除的SKU部分
                                $removedSmtSkuList = array_diff($localSmtSkuList, $onlineSmtSkuList);
                                if ($removedSmtSkuList) {
                                    //删除erp内线上已被删除的SKU部分
                                    foreach ($removedSmtSkuList as $sku) {
                                        smtProductSku::where(['productId' => $productItem['productId'], 'smtSkuCode' => $sku])->delete();
                                    }
                                }
                                unset($localSmtSkuList);
                                unset($onlineSmtSkuList);
                                unset($removedSmtSkuList);
                            } else {
                                smtProductDetail::create($productDetail);
                            }
                            unset($productDetail);
                            foreach ($productInfo['aeopAeProductSKUs'] as $skuItem) {
                                //根据属性值来判断是不是属于海外仓 --海外仓的产品SKU可能还是会一样的
                                $valId = $this->checkProductSkuAttrIsOverSea($skuItem['aeopSKUProperty']);
                                $skuData = array();
                                $skuData['aeopSKUProperty'] = serialize($skuItem['aeopSKUProperty']);
                                $sku_arr = $this->_buildSysSku(trim($skuItem['skuCode']));
                                if ($sku_arr) {
                                    foreach ($sku_arr as $sku_new) {
                                        //计算最低售价和折扣率
                                        if (!empty($sku_new)) {
                                            $rs1 = $this->_countProductLowerPrice($sku_new, $skuItem['skuPrice'], $shipArray);
                                            $lowerPrice = $rs1['status'] ? round($rs1['lowerPrice'], 2) : 0;
                                            $disCountRate = $rs1['status'] ? floor((1 - $lowerPrice / $skuItem['skuPrice']) * 100) : 0;
                
                                        } else {
                                            $lowerPrice = 0;
                                            $disCountRate = 0;
                                        }
                
                                        $skuData['skuCode'] = $sku_new;
                                        $skuData['skuMark'] = $productItem['productId'] . ':' . $sku_new;
                                        $skuData['smtSkuCode'] = $skuItem['skuCode'];
                                        $skuData['skuPrice'] = $skuItem['skuPrice'];
                                        $skuData['ipmSkuStock'] = $skuItem['ipmSkuStock'];
                                        $skuData['productId'] = $productItem['productId'];
                                        $skuData['sku_active_id'] = $skuItem['id'];
                                        $aeopSKUProperty = array_shift($skuItem['aeopSKUProperty']);
                                        $skuData['propertyValueId'] = $aeopSKUProperty['propertyValueId'];
                                        $skuData['skuPropertyId'] = $aeopSKUProperty['skuPropertyId'];
                                        $skuData['propertyValueDefinitionName'] = array_key_exists('propertyValueDefinitionName', $aeopSKUProperty) ? $aeopSKUProperty['propertyValueDefinitionName'] : '';
                                        $skuData['synchronizationTime'] = date('Y:m:d H:i:s', time());
                                        $skuData['updated'] = 1;
                                        $skuData['overSeaValId'] = $valId;
                                        $skuData['lowerPrice'] = $lowerPrice;
                                        $skuData['discountRate'] = $disCountRate;
                                        $skuInfo = smtProductSku::where(['smtSkuCode' => $skuItem['skuCode'], 'productId' => $productItem['productId']])->first();
                                        if ($skuInfo) {
                                            smtProductSku::where(['smtSkuCode' => $skuItem['skuCode'], 'productId' => $productItem['productId']])->update($skuData);
                                        } else {
                                            smtProductSku::create($skuData);
                                            $plat_info = $this->_getProductPlatInfoByPlatType($channel, 'SMT');
                                            $array = array(
                                                'SKU' => $sku_new,
                                                'userID' => $user_id,
                                                'publishTime' => $product['gmtCreate'],
                                                'platTypeID' => $plat_info['platTypeID'],
                                                'publishPlat' => $plat_info['platID'],
                                                'sellerAccount' => $account->account,
                                                'itemNumber' => $productItem['productId'],
                                                'publishViewUrl' => $product['product_url']
                                            );
                                            SkuPublishRecords::create($array);
                                        }
                                    }
                                }
                            }
                        }
                        $currentPage++;
                    } else {
                        $flag = false;
                    }
                } else {
                    echo $productList['error_code'] . ':' . $productList['error_message'];
                    $flag = false;
                }
            }
           

            
        }
        $end = microtime(true);
        echo 'Running time ' . round($end - $start, 3) . ' seconds';

    }

    /**
     * 从速卖通SKU中提取不带前后缀的SKU
     * @param $skuCode
     * @return mixed
     */
    public function getSkuCode($skuCode){
        $skuTemp  = $skuCode;
        $skuTempA = (strpos($skuTemp,"*") !== false) ? strpos($skuTemp,"*") : -1;
        $skuTempB = (strpos($skuTemp,"#") !== false) ? strpos($skuTemp,"#") : strlen($skuTemp);
        $skuTemp  = substr($skuTemp,$skuTempA+1,$skuTempB-$skuTempA-1);
        return $skuTemp;
    }

    /**
     * 获取速卖通销售前缀
     * @param $sku
     * @return string
     */
    public function get_skucode_prefix($sku)
    {
        $len = 0;
        $prefix = '';
        if (($len = stripos($sku, '*')) > 0) {
            $prefix = substr($sku, 0, $len);
        }
        return strtoupper(trim($prefix));
    }

    private function parseDateString($str){
        return date ( 'Y-m-d H:i:s', strtotime ( mb_substr ( $str, 0, 14 ) ) );
    }

    /**
     * 获取SKU属性中的海外仓发货属性
     * @param $aeopSKUProperty
     * @return int
     */
    protected function checkProductSkuAttrIsOverSea($aeopSKUProperty){
        $valId = 0;
        if (!empty($aeopSKUProperty)){
            foreach ($aeopSKUProperty as $property){
                if ($property['skuPropertyId'] == 200007763){ //发货地的属性ID
                    $valId = $property['propertyValueId'];
                    break;
                }
            }
        }
        return $valId;
    }

    /**
     * 获取定义的专为SMT打折定义的物流
     * @return array
     */
    private function _getSmtDefinedShipForDiscount(){
        $data = array();
        $sysInfo = ErpSystem::where('system_value_id',97)->first();
        if (empty($sysInfo)){ //没有数据，直接返回了
            return $data;
        }

        //开始解析下
        $shipList = explode(chr(13), $sysInfo->system_value);
        if (!empty($shipList)){
            foreach ($shipList as $row){
                list($pre, $val) = explode('|', $row);

                if (trim($pre) == 'gt15'){
                    $temp = explode(';', $val);
                    foreach($temp as $v){
                        list($k, $v) = explode(':', $v);
                        $data['gt15'][trim($k)] = trim($v);
                    }
                }else if (trim($pre) == 'le15'){
                    $temp = explode(';', $val);
                    foreach($temp as $v){
                        list($k, $v) = explode(':', $v);
                        $data['le15'][trim($k)] = trim($v);
                    }
                }
            }
        }
        return $data;
    }

    public function _buildSysSku($skuCode){
        $skus = $this->getSkuCode($skuCode) ;

        $sku_list = explode('+', $skus); // 处理组合的SKU：DA0090+DA0170+DA0137
        $sku_arr  = array();
        foreach ($sku_list as $value) {
            $len = strpos($value, '('); // 处理有捆绑的SKU：MHM330(12)
            $sku_new = $len ? substr($value, 0, $len) : $value;
            $sku_arr[] = $sku_new;
        }
        return !empty($sku_arr) ? $sku_arr : array('');
    }

    public function _countProductLowerPrice($sku, $price, $shipArray){
        if (empty($shipArray)){
            return array('status' => false, 'lowerPrice' => 0, 'info' => '没有定义相应物流');
        }

        //先判断SKU是否存在，不存在不计算
        $sku = str_replace('{YY}', '', $sku);
        $sku = trim($sku);
        $product = ProductModel::where('model',$sku)->first();
        if (empty($product)){
            return array('status' => false, 'lowerPrice' => 0, 'info' => 'SKU不存在');
        }
        //根据售价及产品信息来确定物流
        $shipmentId = $this->_chooseProductShip($product, $price, $shipArray); //获取物流
        if (empty($shipmentId)){
            return array('status' => false, 'lowerPrice' => 0, 'info' => '物流不存在');
        }

        //计算成本: 运费+采购成本+平台费

        //平台费率
        $salePlat    = ErpSalesPlatform::where('platID',5)->first()->toArray();// 获取速卖通平台费率等信息
        $platFee     = $salePlat['platOperateFee']; //平台操作费
        $platFeeRate = $salePlat['platFeeRate']; //平台费率

        //运费
        $shipFee = $this->getShipFee($shipmentId, $product->products_weight);

        //成本价
        $cost = $product->purchase_price;
        if ($shipFee <= 0 || $cost <= 0){
            return array('status' => false, 'lowerPrice' => 0, 'info' => '成本价或运费不存在');
        }
        $this->_CNYExchangeRate = CurrencyModel::where('code','RMB')->first()->rate;
        //售价 = ((成本价+运费+物流操作费)/美元汇率+ 平台固定操作费)/(1-利润率-成交费率)
        $lowerPrice = (($cost + $shipFee)/$this->_CNYExchangeRate + $platFee)/(1 - $this->_lowerProfitRate/100 - $platFeeRate/100);
        return array('status' => true, 'lowerPrice' => $lowerPrice, 'info' => '');
    }

    /**
     * 根据产品信息和售价来确定物流
     * @param $productInfo
     * @param $price
     * @param $shipArray
     * @return int
     */
    private function _chooseProductShip($productInfo, $price, $shipArray){
        $shipmentId = 0;
        if(count($productInfo->wrapLimit)){
            $product_limit_type = $productInfo->wrapLimit->name;
        }else{
            return $shipmentId;
        }


        /*
        if ($price > 10){ //15美金以上
            if ($productInfo['products_with_battery']){ //带电
                $shipmentId = isset($shipArray['gt15']['battery']) ? $shipArray['gt15']['battery'] : 0;//151;
            }elseif ($productInfo['products_with_fluid'] || $productInfo['products_with_powder']){ //液体粉末
                $shipmentId = isset($shipArray['gt15']['fluid']) ? $shipArray['gt15']['fluid'] : 0;//317;
            }else { //普货
                $shipmentId = isset($shipArray['gt15']['other']) ? $shipArray['gt15']['other'] : 0;//28;
            }
        }else { //15美金或以下
            if ($productInfo['products_with_battery']){ //带电
                $shipmentId = isset($shipArray['le15']['battery']) ? $shipArray['le15']['battery'] : 0;//273;
            }elseif ($productInfo['products_with_fluid'] || $productInfo['products_with_powder']){ //液体粉末
                $shipmentId = isset($shipArray['le15']['fluid']) ? $shipArray['le15']['fluid'] : 0;//316;
            }else { //普货
                $shipmentId = isset($shipArray['le15']['other']) ? $shipArray['le15']['other'] : 0;//291;
            }
        }*/
        if ($price > 10){
            if ($product_limit_type == 'battery')  {//带电
                $shipmentId = isset($shipArray['gt15']['battery']) ? $shipArray['gt15']['battery'] : 0;//151;
            }elseif ($product_limit_type == 'fluid' || $product_limit_type == 'powder'){
                $shipmentId = isset($shipArray['gt15']['fluid']) ? $shipArray['gt15']['fluid'] : 0;//317;
            }else {
                $shipmentId = isset($shipArray['gt15']['other']) ? $shipArray['gt15']['other'] : 0;//28;
            }
        }else{
            if ($product_limit_type == 'battery')  {//带电
                $shipmentId = isset($shipArray['le15']['battery']) ? $shipArray['le15']['battery'] : 0;//273;
            }elseif ($product_limit_type == 'fluid' || $product_limit_type == 'powder'){
                $shipmentId = isset($shipArray['le15']['fluid']) ? $shipArray['le15']['fluid'] : 0;//316;
            }else {
                $shipmentId = isset($shipArray['le15']['other']) ? $shipArray['le15']['other'] : 0;//291;
            }
        }
        return $shipmentId;
    }

    /**
     * 根据物流ID和重量计算运费
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getShipFee($id, $weight){
        $shipmentInfo = ZoneModel::where('logistics_id',$id)->first();
        if (empty($shipmentInfo)){
            return 0;
        }
        //$shipmentCalculateElementArray = unserialize($shipmentInfo['shipmentCalculateElementArray']);
        //运费 = 首重费用 + {[总重 - 首重] ÷ 续重} * 续重费用 + 操作费
        $firstFee         = $shipmentInfo->fixed_price;
        $firstWeight      = $shipmentInfo->fixed_weight;
        $additionalFee    = $shipmentInfo->continued_price;
        $additionalWeight = $shipmentInfo->continued_weight;
        $operateFee       = $shipmentInfo->price;
        $shipFee = $firstFee + ceil(($weight - $firstWeight) / $additionalWeight) * $additionalFee + $operateFee;
        return $shipFee;
    }

    /**
     * 根据ProductId获取erp中SKU列表
     * @param $productId
     * @return array
     */
    public function getLocalSmtSkuCodeBy($productId){
        $skuDataList = smtProductSku::where('productId',$productId)->get()->toArray();
        $return = array();
        if($skuDataList){
            foreach ($skuDataList as $skuItem){
                $return[] = strtoupper($skuItem['smtSkuCode']);
            }
            $return = array_unique($return);
        }
        return $return;
    }

    public function _getProductPlatInfoByPlatType(AliexpressAdapter $AliexpressAdapter,$platType){
        $products_plat = $AliexpressAdapter->defineProductPublishPlatArray();
         foreach ($products_plat as $key => $value) {
             if($value['platType'] == $platType){
                 return $value;
             }
         }
        return false;
    }



}
