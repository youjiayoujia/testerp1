<?php

namespace App\Console\Commands;

use Channel;
use Illuminate\Console\Command;
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
        if ($account) {
            $channel = Channel::driver($account->channel->driver, $account->api_config);
           
            $flag = true;
            $start = microtime(true);
            $currentPage = 1;
            $productDetail = array();
            $product = array();
            $productSKU = array();
            while($flag){
                $productList = $channel->getOnlineProduct($currentPage, 100);
                if(array_key_exists('success', $productList) && $productList['success']){
                    foreach($productList['aeopAEProductDisplayDTOList'] as $productItem) {
                        $productInfo = $channel->findAeProductById($productItem['productId']);
                        $product['productId'] = $productItem['productId'];
                        $product['token_id'] = $account->id;
                        $product['subject'] = $productInfo['subject'];
                        $product['productPrice'] = $productInfo['productPrice'];
                        $product['productStatusType'] = $productInfo['productStatusType'];
                        $product['ownerMemberId'] = $productInfo['ownerMemberId'];
                        $product['ownerMemberSeq'] = $productInfo['ownerMemberSeq'];
                        $product['wsOfflineDate'] = $productInfo['wsOfflineDate'];
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
                        $productItem['gmtCreate'] = $productItem['gmtCreate'];
                        $productItem['gmtModified'] = $productItem['gmtModified'];
                        $res = smtProductList::where('productId', $productItem['productId'])->first();
                        if ($res) {
                            smtProductList::where('productId', $productItem['productId'])->update($product);
                        } else {
                            smtProductList::create($product);
                        }
    
                        $productDetail['productId'] = $productItem['productId'];
                        $productDetail['aeopAeProductPropertys'] = serialize($productInfo['aeopAeProductPropertys']);
                        $productDetail['imageURLs'] = $productInfo['imageURLs'];
                        $productDetail['detail'] = $productInfo['detail'];
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
            }
            $end = microtime(true);
            echo '耗时' . round($end - $start, 3) . '秒';

        }
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

}
