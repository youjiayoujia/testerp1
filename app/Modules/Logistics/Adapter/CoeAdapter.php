<?php
namespace App\Modules\Logistics\Adapter;

use App\Models\PackageModel;
use App\Models\LogisticsModel;

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/8/1
 * Time: 下午13:31
 */

class CoeAdapter implements AdapterInterface
{
    public $logisticId = 0;

    /**
     *寄件人信息
     */
    private $senderInfo = array(
        'j_company' => 'Shenzhen Salamoer Technology',      //寄件人公司
        'j_contact' => 'huangchaoyun',                     //寄件人
        'j_tel' => '18038094536',                 //电话
        'j_address' => 'A3-2 Hekan Industrial Zone No.41 Wuhe Road South', //地址
        'j_province' => 'GUANGDONG',                    //省
        'j_city' => 'SHENZHEN',                        //市
        'j_post_code' => '518129',                 //邮编
        'j_country' => 'CN'
    );

    public $logisticConfig = [
        'url'=>'http://112.74.141.18:9000/coeapi/coeSync/saveCoeOrder.do',
        'UserId'=>'SZE150401',
        'UserPassword'=>'SZE150401Mima20150902',
        'Key'=>'7891524B3896284F496775CCEA10F32C',
    ];

    public function __construct($config)
    {
        if ($config["logisticId"]) {
            $this->logisticId = $config["logisticId"];
        }
    }

    public function placeOrder() {

        $logisticPackages = PackageModel::where(['logistics_id' => $this->logisticId, "tracking_no"=>""])->get();

        foreach ($logisticPackages as $package) {



            $result = $this->doUpload($package);

            if($result['status'] == 0){
                echo "---------package id: ".$result['package_id']." upload order infomation failed.<br>";
            }else{
                preg_match('/<jobNo.*>(.*)<\/jobNo>/isU',$result['msg'],$shippingcode);
                preg_match('/<success.*>(.*)<\/success>/isU',$result['msg'],$restatus);

                //成功获取追踪号
                if($restatus[1] == 'true'){
                    $shipcode = $shippingcode[1];
                    PackageModel::where(["id"=>$package->id])->update("tracking_no", $shipcode);

                    echo "package id: ".$result['package_id']." upload order infomation success.<br>";

                } else {
                    echo "package id: ".$result['package_id']." upload order infomation failed.<br>";
                }


            }

        }


        echo "--------finish-----------";
        exit;

    }

    public function doUpload($package) {
        $logisticsInfo = $package->logistics;
        $hub = $logisticsInfo->type;
        $countryCode = $package->shipping_country;//发货国家简码
        $countryCode = $countryCode == "UK"? "GB" : $countryCode;
        $buyer_name =  $package->shipping_firstname . " ". $package->shipping_lastname;


        $totalCount = 0;//内件数量
        $totalWeight = 0;//货品重量，千克（三位小数）
        $totalValue = 0;//货品申报价值(两位小数)  暂时表中数据不确定准哪里
        $itemStr = "";
        $declareNameEn = "";

        foreach ($package->items as $key=>$item) {
            $totalCount += $item->quantity;
            $totalWeight += $item->item->weight;
            $descrName = $item->item->name;

            if($key == 0){
                $declareNameEn =  $item->item->name;
            }

            $itemStr .="<item>
						      <descrName><![CDATA[".$descrName."]]></descrName>
						      <pcs>". $item->quantity."</pcs>
						      <unitPrice>". $item->orderItem->price."</unitPrice>
						      <totalPrice>".$item->orderItem->price * $item->quantity . "</totalPrice>
						      <cur>USD</cur>
		    			  </item>";

        }

        $order = $item->order;
        $total_value = round($order->amount, 2);

        $content ="
		<logisticsEventsRequest>
		<logisticsEvent>
		<eventHeader>
			<eventType>LOGISTICS_PACKAGE_SEND</eventType>
			<eventMessageId><![CDATA[" . $order->ordernum . "-SLME-2016-COT-B76EFD991B19]]> </eventMessageId>
			<eventTime><![CDATA[".date("Y-m-d H:i:s")."]]></eventTime>
			<eventSource><![CDATA[SZE150401]]></eventSource>
			<eventTarget>COE</eventTarget>
		</eventHeader>
		<eventBody>
			<orders>
				<order>
					<referenceID>".$order->ordernum."</referenceID>
					<paymentType>PP</paymentType>

					<pcs>".$totalCount."</pcs>

					<destNo><![CDATA[". $countryCode ."]]></destNo>
					<date><![CDATA[".date("Y-m-d H:i:s")."]]></date>
					<custNo><![CDATA[".$this->logisticInfo['UserId']."]]></custNo>
					<weight>".$totalWeight."</weight>
					<declaredValue>".$totalValue."</declaredValue>
					<declaredCurrency>USD</declaredCurrency>
					<contents><![CDATA[".$declareNameEn."]]></contents>
					<isReturnLabel>0</isReturnLabel>
					<isInsure>0</isInsure>
					<hub>".$hub."</hub>0
					<sendContact>
						<companyName><![CDATA[".$this->senderInfo['j_company']."]]></companyName>
						<personName><![CDATA[".$this->senderInfo['j_contact']."]]></personName>
						<countryCode><![CDATA[".$this->senderInfo['j_country']."]]></countryCode>
						<phoneNumber><![CDATA[".$this->senderInfo['j_tel']."]]></phoneNumber>
						<divisioinCode><![CDATA[".$this->senderInfo['j_province']."]]></divisioinCode>
						<city><![CDATA[".$this->senderInfo['j_city']."]]></city>
						<address1><![CDATA[".$this->senderInfo['j_address']."]]></address1>
						<postalCode><![CDATA[".$this->senderInfo['j_post_code']."]]></postalCode>
					</sendContact>
					<receiverContact>
						<companyName><![CDATA[". $buyer_name ."]]></companyName>
						<personName><![CDATA[".$buyer_name."]]></personName>
						<countryCode><![CDATA[".$countryCode."]]></countryCode>
						<phoneNumber><![CDATA[".$package->shipping_phone."]]></phoneNumber>
						<divisioinCode><![CDATA[".$package->shipping_state."]]></divisioinCode>
						<city><![CDATA[".$package->shipping_city."]]></city>
						<address1><![CDATA[".$package->shipping_address." ]]></address1>
						<address2><![CDATA[".$package->shipping_address1." ]]></address2>
						<postalCode><![CDATA[".$package->shipping_zipcode."]]></postalCode>
					</receiverContact>
					<items>
						".$itemStr."
					</items>
					</order>
				</orders>
			</eventBody>
		</logisticsEvent>
		</logisticsEventsRequest>";

        $content = urlencode($content);

        $headers = array("application/x-www-form-urlencoded; charset=gb2312");
        $postData =array(
        );

        $url = $this->getLogisticUrl($content);

        $result = $this->curlPost($url, $postData, $headers);

        $result['package_id'] = $package->id;

        return $result;
    }

    public function curlPost($url, $request_json, $headers){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
        $data = curl_exec($ch);

        $return = ['status'=>0, 'msg'=>''];

        if (curl_errno($ch)) {
            $return['msg'] = curl_error($ch);
        } else {
            curl_close($ch);
            $return['status'] = 1;
            $return['msg'] = $data;
        }

        return $return;
    }

    public function getLogisticUrl($content=""){
        $config = $this->logisticConfig;
        return  $config["url"] . "?Content=".$content."&UserId=".$config['UserId']."&UserPassword=".$config['UserPassword']."&Key=".$config['Key'];
    }

    public function getTracking($data)
    {
        echo 'here is the chukouyi adapter: Function getTracking';
        // TODO: Implement placeOrder() method.
    }

}