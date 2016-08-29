<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2016-07-26
 * Time: 14:23
 */

namespace App\Modules\Logistics\Adapter;
header("Content-type:text/html;charset=utf-8");
use App\Models\Order\ItemModel;

use App\Models\Publish\Wish\WishSellerCodeModel;

use Illuminate\Support\Facades\DB;

set_time_limit(1800);

Class WishyouAdapter extends BasicAdapter
{
	public  $senderInfo;//发件人信息
	private $API_key;//密钥
	
	private $tokenArr = array(
						1 => '5c9d523e2c7140357b7d632f63510da3118',
						2 => 'da19c164f051fdf623af41412fba368e247'
					   );	   
	//wish邮的分仓代码
	private $warehouseArr = array(
								'sh'=>1,//上海仓
								'gz'=>2,//广州仓
								'sz'=>3,//深圳仓  4义乌
								'yw'=>4
							);
							
    //寄件人和揽收人地址（中英文不同）
    private $senderAndLanShou = array(
       'sz' => array(
            'cn' => array(
	    		  'province' => '广东',
				  'city'     => '深圳',
				  'username' => '萨拉摩尔',
				  'phone'    => '18038094536',
				  'address'  => '龙岗区五和大道南41号和磡工业区A3栋二楼'
    		),
    		'en' => array(
	    		  'province' => 'guangdong',
				  'city'     => 'shenzhen',
				  'username' => 'slme',
				  'phone'    => '18038094536',
				  'address'  => 'A3 BLDG on 2F in Hekan IZ,Bantianwuhe South Rd Longgang Dist'
    		)
        ),
		'gz' => array(
            'cn' => array(
	    		  'province' => '广东',
				  'city'     => '深圳',
				  'username' => '萨拉摩尔',
				  'phone'    => '18038094536',
				  'address'  => '龙岗区五和大道南41号和磡工业区A3栋二楼'
    		),
    		'en' => array(
	    		  'province' => 'guangdong',
				  'city'     => 'shenzhen',
				  'username' => 'slme',
				  'phone'    => '18038094536',
				  'address'  => 'A3 BLDG on 2F in Hekan IZ,Bantianwuhe South Rd Longgang Dist'
    		)
        ),
        'yw' => array(
            'cn' => array(
	    		  'province' => '浙江',
				  'city'     => '金华',
				  'username' => '萨拉摩尔',
				  'phone'    => '17705794166',
				  'address'  => '浙江省金华市金东区鞋塘镇金港大道西2011号金义邮政电子商务示范园'
    		),
    		'en' => array(
	    		  'province' => 'zhejiang',
				  'city'     => 'jinhua',
				  'username' => 'slme',
				  'phone'    => '17705794166',
				  'address'  => 'Jin Dong District, Fu Village, town, West 2011, Jin Hong Kong Road West 1 building two'
    		)
        ),
        'sh' => array(
            'cn' => array(
	    		  'province' => '',
				  'city'     => '',
				  'username' => '',
				  'phone'    => '',
				  'address'  => ''
    		),
    		'en' => array(
	    		  'province' => 'shanghai',
				  'city'     => 'shanghai',
				  'username' => 'slme',
				  'phone'    => '18038094536',
				  'address'  => 'No.1208 Baise Road,Wish storehouse,Xuhui District Shanghai China,200237'
    		)
        )
    );

    public function __construct($config)
    {
        
    }

    /*
	*Time:2016-07-25
	*Upload postal tracking numbers wish to obtain
	*@Jason
	*/
    public function getTracking($orderInfo)
    {
		
		if(!$orderInfo){
	     $result_arr['msg']="订单数据为空";
	     return $result_arr;
	    }
		$result_arr['status'] = 0;
	 	$result_arr['msg'] = '';
		$otype = '0';
		if(($orderInfo->order->channel_id == '486') || ($orderInfo->order->channel_id == '487')) {  //根据物流渠道区分 here (测试渠道数据)
			$otype ='11-0';
		}
		if(($orderInfo->order->channel_id == '515') || ($orderInfo->order->channel_id == '516')) {  //渠道 here test
			$otype ='10-0';
		}
		if($orderInfo->tracking_no){
	     $result_arr['msg']="订单{$orderInfo->id}的追踪号已存在";
	     return $result_arr;
	    }
		if(!$orderInfo->warehouse_id){
			continue;
		}
		$this->API_key = $this->tokenArr[$orderInfo->warehouse_id];  //根据仓库选择对应  test here
		
		$order_xml=$this->createRequestXmlFile($orderInfo,$otype);  //xml
		
		 if(!$order_xml){
		     $result_arr['msg']="订单xml创建失败";
		     return $result_arr;
		 }
	   $url = 'http://www.shpostwish.com/api_order.asp';

	   $call=$this->postCurlData($url,$order_xml);

	   $result=simplexml_load_string($call);
	   $mess = '';
	   foreach($result as $key=>$v){
	   		if(preg_match("/error/",$key)){
	   			$mess = $v;
	   		};
	   }
	   if( ($result->status == 0) && !empty($result->barcode) ){
	   	  $result->barcode = trim($result->barcode);
		  $re = DB::table("packages")->where('id',$orderInfo->id)->update(
					['tracking_no' => $result->barcode,
					]);
	   	  if($re){
	   	     $result_arr['msg'] = '订单号为'.$orderInfo->id.'的订单上传成功，运单号为'.$result->barcode;
	   	  }else{
	   	     $result_arr['msg'] = '订单号为'.$orderInfo->id.'的订单数据更新失败，运单号为'.$result->barcode;
	   	  }
	   	  $result_arr['status'] = 1;
	   }else{
	   		if($result->error_message == ''){
	   			$result->error_message = $mess;
	   		}
	        $result_arr['msg'] = '订单号为'.$orderInfo->id.'的订单上传失败，错误信息'.$result->error_message;
	   }
	   return $result_arr;
    }
	/**
	 * 构造添加订单的请求xml文件
	 */
	public function createRequestXmlFile($orderInfo,$otype){
		 
		 $xmlStr = '<?xml version="1.0" encoding="UTF-8"?>';
		 $xmlStr .='
		 			<orders>
		 			  <api_key>'.$this->API_key.'</api_key>
		 			  <mark></mark>
		 			  <bid>'.rand(0,99999).'</bid>
		 		   ';
			$buyer_address = $orderInfo->shipping_address.' '.$orderInfo->shipping_address1;  //test here two address
		    $buyer_country = '';
		    $buyer_country_code = '';
		    if($orderInfo->order->billing_country){
				$country_info = DB::table("wish_base_country")->where("country_code",$orderInfo->order->billing_country)->first();  //create wish_base_country object
		      }else{
				$country_info = DB::table("wish_base_country")->where("country_code",$orderInfo->order->shipping_country)->first();  
		      }
		    
		    if(!empty($country_info)){
		      $buyer_country = $country_info->country_en;
		      $buyer_country_code = $country_info->country_code;
		    }else{
		      $buyer_country = $orderInfo->order->shipping_country;
		      $buyer_country_code = $orderInfo->order->shipping_country;
		    }
			
		    $total_count = 0;//内件数量
		    $total_weight = 0;//货品重量，千克（三位小数）
		    $total_value = 0;//货品申报价值(两位小数)
		    $content = '';//内件物品的详细名称（英文）
		    $buyer_phone = '000000';//电话默认为0
		    if(!empty($orderInfo->shipping_phone)){
		      $buyer_phone=$orderInfo->shipping_phone;
		    }
		    $wishID = $orderInfo->id;//wish订单号  here test
		    $buyer_state = $orderInfo->shipping_city;//上传的省，默认是用城市名代替
		    
			$wishID = $orderInfo->transaction_number;   //交易编号
		    //上传的省为空用逗号代替
		    if(!empty($orderInfo->shipping_state)){
		      $buyer_state = $orderInfo->shipping_state;
		    }
		     foreach($orderInfo->items  as $key => $item){
		      $total_count += $item->quantity;
		      $total_weight += $item->quantity*$item->item->weight;
		    }
		    $total_value = round($orderInfo->order->amount,2);
		    $content = $item->item->product->declared_en;   //申报英文名  test $allNeedData['productsInfo'][0]['products_declared_en']
		    
		    //here test senderAndLanShou  $shipInfo   yw_channel   $orderInfo->logistics->type  = type字段
		    $xmlStr .='
		      <order>
		        <guid>'.$orderInfo->order->ordernum.'</guid>
		        <otype>'.$otype.'</otype>
		        <from>'.$this->senderAndLanShou[$orderInfo->logistics->type]['en']['username'].'</from>
		        <sender_province>'.$this->senderAndLanShou[$orderInfo->logistics->type]['en']['province'].'</sender_province>
		        <sender_city>'.$this->senderAndLanShou[$orderInfo->logistics->type]['en']['city'].'</sender_city>
		        <sender_addres>'.$this->senderAndLanShou[$orderInfo->logistics->type]['en']['address'].'</sender_addres>
		        <sender_phone>'.$this->senderAndLanShou[$orderInfo->logistics->type]['en']['phone'].'</sender_phone>
		        <to>'.$orderInfo->shipping_firstname.'</to>
		        <recipient_country>'.$buyer_country.'</recipient_country>
		        <recipient_country_short>'.$buyer_country_code.'</recipient_country_short>
		        <recipient_province>'.$buyer_state.'</recipient_province>
		        <recipient_city>'.$orderInfo->shipping_city.'</recipient_city>
		        <recipient_addres>'.$buyer_address.'</recipient_addres>
		        <recipient_postcode>'.$orderInfo->shipping_zipcode.'</recipient_postcode>
		        <recipient_phone>'.$buyer_phone.'</recipient_phone>
		        <to_local></to_local>
		        <recipient_country_local></recipient_country_local>
		        <recipient_province_local></recipient_province_local>
		        <recipient_city_local></recipient_city_local>
		        <recipient_addres_local></recipient_addres_local>
		        <type_no>4</type_no>
		        <from_country>China</from_country>
		        <user_desc>'.$orderInfo->id.'</user_desc>
		        <content>'.$content.'</content>
		        <num>'.$total_count.'</num>
		        <weight>'.round($total_weight,3).'</weight>
		        <single_price>'.$total_value.'</single_price>
		        <trande_no>'.$wishID.'</trande_no>
		        <trade_amount>'.$total_value.'</trade_amount>
		        <receive_from>'.$this->senderAndLanShou[$orderInfo->logistics->type]['cn']['username'].'</receive_from>
		        <receive_province>'.$this->senderAndLanShou[$orderInfo->logistics->type]['cn']['province'].'</receive_province>
		        <receive_city>'.$this->senderAndLanShou[$orderInfo->logistics->type]['cn']['city'].'</receive_city>
		        <receive_addres>'.$this->senderAndLanShou[$orderInfo->logistics->type]['cn']['address'].'</receive_addres>
		        <receive_phone>'.$this->senderAndLanShou[$orderInfo->logistics->type]['cn']['phone'].'</receive_phone>
		        <warehouse_code>'.$this->warehouseArr[$orderInfo->logistics->type].'</warehouse_code>
		        <doorpickup>1</doorpickup>
		      </order>
		    ';
		 $xmlStr .='</orders>';
	 return $xmlStr; 
	}
	/**
	 * Curl http Post 数
	 * 使用方法：
	 * $post_string = "app=request&version=beta";
	 * postCurlData('http://www.test.cn/restServer.php',$post_string);
	 */
	public function postCurlData($remote_server, $post_string) {
		$ch = curl_init(); 
		$header[] = "Content-type: text/xml";//定义content-type为xml 
		curl_setopt($ch, CURLOPT_URL, $remote_server); //定义表单提交地址 
		curl_setopt($ch, CURLOPT_POST, 1);   //定义提交类型 1：POST ；0：GET 
		curl_setopt($ch, CURLOPT_HEADER, 0); //定义是否显示状态头 1：显示 ； 0：不显示 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//定义请求类型 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//定义是否直接输出返回流 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string); //定义提交的数据，这里是XML文件 
		$tmpInfo = curl_exec ( $ch ); // 执行操作

		 curl_close($ch);//关闭
		 return $tmpInfo;
	}
	
}