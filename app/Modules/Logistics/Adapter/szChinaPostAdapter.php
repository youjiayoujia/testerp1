<?php
/**
 * Created by PhpStorm.
 * User: Guoou
 * Date: 2016/8/4
 * Time: 14:33
 */

namespace App\Modules\Logistics\Adapter;


class szChinaPostAdapter
{
    private $doSql;
    private $orderManage;//订单对象
    private $products;//订单产品对象
    private $server_url ;
    private $soapClient ;
    /**
     * 实例化类
     */
    public function __construct(){
        try{
            $this->doSql=new doSql();
            $this->orderManage=new ordersManage();
            $this->products=new productsManage();
        }
        catch(Exception $e){
            die($e->getMessage());
        }
    }

    //创建订单
    public function create_order($orderID){

        $this->server_url = 'http://www.520post.com/services/mailSearch?wsdl';

        $this->soapClient = new soapclient($this->server_url);

        $upload_data = array();//要上传数据

        $msg = '';//要返回的信息

        $orderId = '';//当天要上传的批次号

        $pageno = '';//当天要上传的包裹号

        $in_resutl = array();//先判断当天推送的记录
        $time = date('Y-m-d');
        $in_resutl = $this->doSql->getOne("select * from erp_china_post_detail where createTime='{$time}'");
        if(!empty($in_resutl)){
            $orderId = $in_resutl['orderId'];
            $pageno = $in_resutl['packageNo'];
        }

        $upload_data['custName']  = '萨拉摩尔电子商务';
        $upload_data['loginName'] = '萨拉摩尔电子商务';
        $upload_data['loginPwd']  = '88316675d7882e3fdbe066000273842c';
        $upload_data['transCode']  = 'HK';
        $upload_data['busType']  = '5';
        $upload_data['orderId']  = $orderId;//当天要上传的批次号
        $upload_data['packageNo']  = $pageno;//当天要上传的包裹号

        $allNeedData['ordersInfo']=$this->orderManage->getOrderInfo($orderID);

        if(empty($allNeedData['ordersInfo']['orders_shipping_code'])){
            $msg = '<span style="color:red;">订单号'.$orderID.'追踪号不存在</span><br/>';
            return $msg;
        }

        $allNeedData['productsInfo']=$this->products->getOrderProducts($allNeedData['ordersInfo']['erp_orders_id'],$allNeedData['ordersInfo']['orders_warehouse_id']);

        //申报价值，去订单总金额，不能超过20美元
        $declave_value = 0;

        $declave_values = round($allNeedData['ordersInfo']['orders_total']/$allNeedData['ordersInfo']['currency_value'],2);

        $declave_value = ($declave_values>20) ? 20 : $declave_values;

        $countryArr = array();

        //通过国家简码找 有可能国家简码未填写
        $sql = 'SELECT * FROM `erp_country` WHERE country_en = "'.$allNeedData['ordersInfo']['buyer_country_code'] .'"';
        $query = $this->doSql->getOne($sql);

        if (empty($query))	//如果通过国家简码找不到
        {
            //通过国家全名找
            $sql = 'SELECT * FROM `erp_country` WHERE country_en = "'.$allNeedData['ordersInfo']['buyer_country'] .'"';
            $query = $this->doSql->getOne($sql);
        }

        if (empty($query))	//如果通过国家全名找不到
        {
            //通过国家全名找全名
            $sql = 'SELECT * FROM `erp_country` WHERE display_name = "'.$allNeedData['ordersInfo']['buyer_country'] .'"';
            $query = $this->doSql->getOne($sql);
        }

        if(empty($query)){
            $msg = '<span style="color:red;">订单号'.$orderID.'对应的国家名称在erp里查不到</span><br/>';
            return $msg;
        }

        if(!empty($query)){
            $countryArr = $this->doSql->getOne("select * from erp_szChinaPost_country where country_cn='{$query['country_cn']}'");
        }

        if(empty($countryArr)){
            $msg = '<span style="color:red;">订单号'.$orderID.'对应深圳邮政的国家名称不存在</span><br/>';
            return $msg;
        }

        $total_weight = 0;
        $sigan_weight = 0;
        $total_value = 0;
        foreach($allNeedData['productsInfo'] as $p){
            $total_weight += $p['item_count']*$p['products_weight'];

        }

        //转化为g并向下取整
        $total_weight = floor($total_weight*1000);
        $sigan_weight = floor($allNeedData['productsInfo'][0]['products_weight']*1000);

        $upload_data['mailList'][0] =array(
            'countryCode'     => $countryArr['country_code'],
            'countryName'     => $countryArr['country_cn'],
            'receiverName'    => $allNeedData['ordersInfo']['buyer_name'],
            'receiverAddress' => $allNeedData['ordersInfo']['buyer_address_1'].' '.$allNeedData['ordersInfo']['buyer_address_2'],
            'receiverPhone'   => $allNeedData['ordersInfo']['buyer_phone'],
            'mailWeight'      => $total_weight,
            'mailCode'        => $allNeedData['ordersInfo']['orders_shipping_code'],
            'receiverCountry' => $query['country_en'],
            'receiverArea'    => $allNeedData['ordersInfo']['buyer_state'],
            'receiverCity'    => $allNeedData['ordersInfo']['buyer_city'],
            'senderName'      => 'SLME',
            'senderProvince'  => 'GUANGDONG',
            'senderCity'      => 'SHENZHEN',
            'senderAddress'   => 'A3 BLDG on 2F in Hekan IZ,Bantianwuhe South Rd Longgang Dist',
            'senderPhone'     => '18038094536',
            'mailInnerType'   => '1',
            'mailInnerName'   => $allNeedData['productsInfo'][0]['products_declared_en'],
            'mailInnerEngName'=> $allNeedData['productsInfo'][0]['products_declared_en'],
            'mailInnerWeight' => $sigan_weight,
            'mailInnerAmount' => count($allNeedData['productsInfo']),
            'mailPrice'       => $declave_value,
            'produceAddress'  => 'china',
            'localMailCode'   => '',
            'transType'       => '1',
            'deliveryPost'    => $allNeedData['ordersInfo']['buyer_zip'],
            'definedNo'       => '',
            'senderPostCode'  => '518000',
            'accUserMobilePhone'=> '',
            'definedOrder'    => ''
        );

        $upload_data_string = json_encode($upload_data);

        $result = $this->soapClient->preparePostageMailData(array('in0'=>$upload_data_string));

        $re = json_decode($result->out,true);

        if($re['status']==0){//数据推送成功
            $msg = '<span style="color:green;">订单号'.$orderID.'推送成功，批次号：'.$re['orderId'].',包裹号:'.$re['packageNo'].'</span><br/>';

            $sqlOp = "INSERT INTO erp_operate_log(operateUser,operateType,operateMod,operateKey,operateText) VALUES('".$_COOKIE[ 'id' ]."','update','ordersManage','". $orderID ."','推送数据到深圳邮政成功')";
            $this->doSql->query($sqlOp);

            //将推送的批次号和包裹号记录下来（当天第一次推送数据，批次和包裹可以为空，第二次推送不能为空）
            $sqlin = "insert into erp_china_post_detail(erp_orders_id,orderId,packageNo,status,createTime,remark,updateTime) values ('".$orderID."','".$re['orderId']."','".$re['packageNo']."','1','".date('Y-m-d')."','','".date('Y-m-d H:i:s')."')";

            $this->doSql->query($sqlin);

        }else{
            $msg = '<span style="color:red;">订单号'.$orderID.'推送失败，原因'.$re['failMailList'].'</span><br/>';
            //将推送的批次号和包裹号记录下来（当天第一次推送数据，批次和包裹可以为空，第二次推送不能为空）
            $sqlin = "insert into erp_china_post_detail(erp_orders_id,orderId,packageNo,status,createTime,remark,updateTime) values ('".$orderID."','".$re['orderId']."','".$re['packageNo']."','2','".date('Y-m-d')."','".$re['failMailList']."','".date('Y-m-d H:i:s')."')";
            $this->doSql->query($sqlin);
        }

        return $msg;

    }
}