<?php
namespace App\Modules\Logistics\Adapter;
use App\Models\LogisticsModel;
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/8/1
 * Time: 下午13:31
 */
class CommonAdapter implements AdapterInterface
{
    public $config;
    public function __construct($logisticsConfig)
    {
        //todo:直接从物流信息里面读出传入
        $this->config = [
            'j_company' => 'Shenzhen Salamoer Technology',      //寄件人公司
            'j_contact' => 'huangchaoyun',                     //寄件人
            'j_tel' => '18038094536',                 //电话
            'j_address' => 'A3-2 Hekan Industrial Zone No.41 Wuhe Road South', //地址
            'j_province' => 'GUANGDONG',                    //省
            'j_city' => 'SHENZHEN',                        //市
            'j_post_code' => '518129',                 //邮编
            'j_country' => 'CN',
            'url' => 'http://112.74.141.18:9000/coeapi/coeSync/saveCoeOrder.do',
            'UserId' => 'SZE150401',
            'UserPassword' => 'SZE150401Mima20150902',
            'Key' => '7891524B3896284F496775CCEA10F32C',
        ];
        $logistic = LogisticsModel::where(['driver'=>$logisticsConfig["driver"]])->firstOrFail();
        $supplier = $logistic->supplier;
        $emailTemplate = $logistic->emailTemplate;

        $config = [];


        $config['url'] = $supplier->url;
        $config['UserId'] = $supplier->customer_id;
        $config['UserPassword'] = $supplier->password;
        $config['Key'] = $supplier->secret_key;

        $config['j_company'] = $emailTemplate->unit;
        $config['j_contact'] = $emailTemplate->secret_key;
        $config['j_tel'] = $emailTemplate->phone;
        $config['j_address'] = $emailTemplate->address;
        $config['j_post_code'] = $emailTemplate->zipcode;
        $config['j_country'] = $emailTemplate->country_code;
        $config['j_province'] = $emailTemplate->province;
        $config['j_city'] = $emailTemplate->city;

        $this->config = $config;

        //    var_dump($logistic->name);exit;
    }
    public function getTracking($package){

    }
    public function curlPost($url, $request_json, $headers)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
        $data = curl_exec($ch);
        $return = ['status' => 0, 'msg' => ''];
        if (curl_errno($ch)) {
            $return['msg'] = curl_error($ch);
        } else {
            curl_close($ch);
            $return['status'] = 1;
            $return['msg'] = $data;
        }
        return $return;
    }
    public function getLogisticUrl($content = "")
    {
        return $this->config["url"] . "?Content=" . $content . "&UserId=" . $this->config['UserId'] . "&UserPassword=" . $this->config['UserPassword'] . "&Key=" . $this->config['Key'];
    }
}