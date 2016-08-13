<?php
/**
 * Created by PhpStorm.
 * User: Guoou
 * Date: 2016/8/4
 * Time: 14:33
 */

namespace App\Modules\Logistics\Adapter;


class SzChinaPostAdapter extends BasicAdapter
{
    public function getTracking($package){        
        $this->server_url = 'http://www.520post.com/services/mailSearch?wsdl';
        
        $this->soapClient = new \SoapClient($this->server_url, array('encoding'=>'UTF-8'));
        
        $upload_data = array();//要上传数据        
        $msg = '';//要返回的信息        
        $orderId = '';//当天要上传的批次号        
        $pageno = '';//当天要上传的包裹号
        
        $in_resutl = array();//先判断当天推送的记录
        $time = date('Y-m-d');
        //$in_resutl = $this->doSql->getOne("select * from erp_china_post_detail where createTime='{$time}'");
        $in_resutl = array();
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
        
        
        //申报价值，去订单总金额，不能超过20美元
        $declave_value = 0;
        $declave_values = round($package->order->amount / $package->order->rate,2);      
        $declave_value = ($declave_values>20) ? 20 : $declave_values;
        
        $countryCode = $package->shipping_country;
        $countryName = $package->country->cn_name;
        $buyer_name = $package->order->billing_firstname . " " . $package->order->billing_lastname;  //不确定字段在哪个表中
        $totalCount = 0;
        $totalWeight = 0;
        $totalValue = 0;
        $declareNameEn = '';
        $declareName = '';
        $amount = 0;
        foreach ($package->items as $key => $item) {        
          $totalCount += $item->quantity;
          $totalWeight += $item->item->weight;
          $totalValue += $item->item->product->declared_value;
          
          if ($key == 0) {
              $siganWeight = $item->item->weight;
              $declareName = $item->item->product->declared_cn;
              $declareNameEn = $item->item->product->declared_en;
          }
          if($item->item->product){
              $amount ++ ;
          }
        }      
     
        //转化为g并向下取整
        $totalWeight = floor($totalWeight*1000);
        $siganWeight = floor($siganWeight*1000);
        $upload_data['mailList'][0] =array(
          'countryCode'     => $countryCode,
          'countryName'     => $countryName,
          'receiverName'    => $buyer_name,
          'receiverAddress' => $package->order->billing_address.' '.$package->order->billing_address1,
          'receiverPhone'   => $package->shipping_phone,
          'mailWeight'      => $totalWeight,
          'mailCode'        => $package->tracking_no,
          'receiverCountry' => $package->order->billing_country,
          'receiverArea'    => $package->order->billing_state,
          'receiverCity'    => $package->order->billing_city,
          'senderName'      => 'SLME',
          'senderProvince'  => 'GUANGDONG',
          'senderCity'      => 'SHENZHEN',
          'senderAddress'   => 'A3 BLDG on 2F in Hekan IZ,Bantianwuhe South Rd Longgang Dist',
          'senderPhone'     => '18038094536',
          'mailInnerType'   => '1',
          'mailInnerName'   => $declareName,
          'mailInnerEngName'=> $declareNameEn,
          'mailInnerWeight' => $siganWeight,
          'mailInnerAmount' => $amount,
          'mailPrice'       => $declave_value,
          'produceAddress'  => 'china',
          'localMailCode'   => '',
          'transType'       => '1',
          'deliveryPost'    => $package->order->billing_zipcode,
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