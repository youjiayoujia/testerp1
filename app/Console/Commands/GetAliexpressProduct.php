<?php

namespace App\Console\Commands;

use Channel;
use Illuminate\Console\Command;
use App\Http\Controllers\Publish\Smt\SmtProductsController;
use App\Models\Publish\Smt\smtUserSaleCode;
use App\Models\SkuPublishRecords;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Smt\smtProductSku;
use App\Models\Publish\Smt\smtProductDetail;
use App\Models\Publish\Smt\smtProductList;

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
        $_product_statues_type = array("onSelling", "offline", "auditing", "editingRequired");  //商品业务状态
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $smtProductsObj = new SmtProductsController();
        $shipArray = $smtProductsObj->_getSmtDefinedShipForDiscount();
      
        while($flag){
            foreach($_product_statues_type as $type){
                $productList = $channel->getOnlineProduct($type,$currentPage, 100);
                if (array_key_exists('success', $productList) && $productList['success']) {
                    if (array_key_exists('aeopAEProductDisplayDTOList', $productList) && $productList['aeopAEProductDisplayDTOList']) {
                        foreach ($productList['aeopAEProductDisplayDTOList'] as $productItem) {
                            $productInfo = $channel->findAeProductById($productItem['productId']);
                            $product['productId'] = $productItem['productId'];
                            $product['product_url'] = 'http://www.aliexpress.com/item/-/' . $productItem['productId'] . '.html';
                            $product['token_id'] = $account->id;
                            $product['subject'] = array_key_exists('subject', $productInfo) ? $productInfo['subject'] : '';
                            $product['productPrice'] = array_key_exists('productPrice',$productInfo) ? $productInfo['productPrice'] : '';
                            $product['productStatusType'] = $productInfo['productStatusType'];
                            $product['ownerMemberId'] = $productInfo['ownerMemberId'];
                            $product['ownerMemberSeq'] = $productInfo['ownerMemberSeq'];
                            $product['wsOfflineDate'] = $smtProductsObj->parseDateString($productInfo['wsOfflineDate']);
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
                            $product['gmtCreate'] = $smtProductsObj->parseDateString($productItem['gmtCreate']);
                            $product['gmtModified'] = $smtProductsObj->parseDateString($productItem['gmtModified']);
                            $product['multiattribute'] = count($productInfo['aeopAeProductSKUs']) > 1 ? 1 : 0;
                
                            $tempSKU = array_shift($productInfo['aeopAeProductSKUs']);
                            $user_id = '';
                            //获取销售前缀
                            $sale_prefix = $smtProductsObj->get_skucode_prefix($tempSKU['skuCode']);
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
                                $localSmtSkuList = $smtProductsObj->getLocalSmtSkuCodeBy($productItem['productId']);
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
                                $valId = $smtProductsObj->checkProductSkuAttrIsOverSea($skuItem['aeopSKUProperty']);
                                $skuData = array();
                                $skuData['aeopSKUProperty'] = serialize($skuItem['aeopSKUProperty']);
                                $sku_arr = $smtProductsObj->_buildSysSku(trim($skuItem['skuCode']));
                                if ($sku_arr) {
                                    foreach ($sku_arr as $sku_new) {
                                        //计算最低售价和折扣率
                                        if (!empty($sku_new)) {
                                            $rs1 = $smtProductsObj->_countProductLowerPrice($sku_new, $skuItem['skuPrice'], $shipArray);
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
                                            $plat_info = $smtProductsObj->_getProductPlatInfoByPlatType('SMT');
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
                    }           
                } else {
                    echo $productList['error_code'] . ':' . $productList['error_message'];
                    $flag = false;
                }
                $currentPage++;
            }           
        }            
      
        $end = microtime(true);
        echo 'Running time ' . round($end - $start, 3) . ' seconds';

    }

   



}