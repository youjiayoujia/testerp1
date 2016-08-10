<?php
namespace App\Modules\Logistics\Adapter;

use Channel;
use App\Models\Channel\AccountModel;
class SmtAdapter extends BasicAdapter
{
    public function getTracking($package){
        $logistics_id =  $package->logistics_id;
        $orderId = $package->order_id; 
        $channel_account_id = $package->channel_account_id;
       // $channel = explode(',',$package->logistics->yw_channel);
        $warehouseCarrierService = '';
        $onlineLogisticsId = '';
        $result = $this->getOnlineLogisticsInfo($channel_account_id, $orderId);
        if( !empty($result['result']) && $result['success']){
            $trackingNumber = '';
            foreach ($result['result'] as $row){
                //分仓，估计要结合 物流分类+状态 来进行判断获取国际运单号
                if ($row['internationalLogisticsType'] == $warehouseCarrierService && $row['onlineLogisticsId'] == $onlineLogisticsId) { //渠道和物流内单号对应上了
                    $trackingNumber = $row['internationallogisticsId']; //国际运单号
                    break;
                }
            }
        }else{
            echo  0;
        }
    }
    
    /**
     * 获取线上发货物流订单信息
     * @param int $channel_account_id 渠道帐号id
     */
    public function getOnlineLogisticsInfo($channel_account_id,$orderId){
        $account = AccountModel::findOrFail($channel_account_id);
        $smtApi = Channel::driver($account->channel->driver, $account->api_config);
        $action = 'api.getOnlineLogisticsInfo';
        $parameter = 'orderId='.$orderId.'&logisticsStatus=wait_warehouse_receive_goods';
        $result =  $smtApi->getJsonData($action,$parameter);
        return json_decode($result,true);
    }
}

?>