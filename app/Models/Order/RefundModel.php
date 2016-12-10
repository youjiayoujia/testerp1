<?php
/**
 * 订单退款模型
 * modify Norton
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/5/25
 * Time: 下午3:26
 */

namespace App\Models\Order;

use App\Base\BaseModel;
use App\Models\ChannelModel;

class RefundModel extends BaseModel
{
    public $table = 'order_refunds';

    public $searchFields = ['order_id' => '订单号'];

    public $fillable = [
        'order_id',
        'refund_amount',
        'price',
        'refund_currency',
        'refund',
        'reason',
        'type',
        'memo',
        'detail_reason',
        'image',
        'refund_voucher',
        'user_paypal_account',
        'customer_id',
        'channel_id',
        'account_id'
    ];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //状态为待审核 小于 15 USD 的速卖通订单
    public function scopeAliexpress15Usd($query){
        $channel_id = ChannelModel::where('name','Aliexpress')->first();

        return $query->where('channel_id',$channel_id->id)
            ->where('refund_amount','<',15)
            ->where('refund_currency','=','USD')
            ->where('type','FULL');

    }

    public function getReasonNameAttribute()
    {
        $arr = config('order.reason');
        return $arr[$this->reason];
    }

    public function getTypeNameAttribute()
    {
        $arr = config('refund.type');
        return $arr[$this->type];
    }

    public function getRefundNameAttribute()
    {
        $arr = config('refund.refund');
        if(isset($arr[$this->refund])){
            return $arr[$this->refund];
        }
        return '';
    }
    public function getProcessStatusNameAttribute(){
        return config('refund.process')[$this->process_status];
    }

    public function Order(){
        return $this->hasOne('App\Models\OrderModel','id','order_id');
    }
    public function User(){
        return $this->hasOne('App\Models\UserModel','id','customer_id');
    }
    public function Account(){
        return $this->hasOne('App\Models\Channel\AccountModel','id','account_id');
    }

    public function Currency(){
        return $this->hasOne('App\Models\CurrencyModel','code','refund_currency');
    }

    public function OrderItems(){
        return $this->hasMany('App\Models\Order\ItemModel','refund_id','id');
    }

    public function getSKUsAttribute(){
        $items = $this->Order->items;
        $sku ='';
        foreach ($items as $item){
            if($item->is_refund == '1'){
                $sku = empty($sku) ? $item->sku : $sku.','.$item->sku;
            }
        }
        return $sku;
    }

    public function getPaidTimeAttribute(){
        return $this->Order->payment_date;
    }
    public function getChannelNameAttribute(){
        return $this->Order->channel->name;
    }
    public function getOrderRemarksAttribute(){
        $remarks = $this->Order->remarks;
        $html = '<ul>';
        if(!$remarks->isEmpty()){
            foreach ($remarks as $remark){
                $html .= "<li>{$remark->remark} {$remark->created_at}</li>";
            }
        }else{

        }

        $html .= '</ul>';
        return $html;
    }

    public function getCustomerNameAttribute(){
        $name = '无';
        if(!empty($this->customer_id)){
            $name = $this->User->name;
        }
        return $name;
    }

    public function batchProcess($paramAry){
        $ids_ary = explode(',',$paramAry['ids']);
        $collection = $this->find($ids_ary);
        if(!$collection->isEmpty()){
            foreach ($collection as $refund){
                $refund->process_status = $paramAry['process'];
                $refund->save();
            }
            return true;
        }
        return false;
    }

    public function getChannelAccountNameAttribute(){
        return $this->Order->channelAccount->account;
    }

    public function getRefundProductsAttribute(){
        $skus = '';
        if(!$this->OrderItems->isEmpty()){
            foreach ($this->OrderItems as $item){
                $skus .= $item->sku.'*'.$item->quantity.';';
            }
        }
        return $skus;
    }

    public function getRefundOrderLogisticsAttribute(){
        //$this->Order->packages;
        if($this->Order->packages->first()->logistics_id != 0){
            if(!empty($this->Order->packages->logistics)){
                return  $this->Order->packages->logistics->name;
            }else{
                return '无';
            }

        }else{
            return '无';
        }
    }

    public function getRefundOrderShipTimeAttribute(){
        if(!$this->Order->packages->isEmpty()){
            return $this->Order->packages->first()->shipped_at;
        }else{
            return '';
        }
    }

    public function getPakcageWeightAttribute(){
        $weight = 0;
        if(!$this->Order->items->isEmpty()){
            foreach($this->Order->items as $order_item){
                $weight += $order_item->item->weight;
            }
        }
        return $weight;
    }

    public function getAliexpressrefunds(){
        return $this->Aliexpress15Usd()->get();
    }


}