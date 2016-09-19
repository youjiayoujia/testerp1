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

class RefundModel extends BaseModel
{
    protected $table = 'order_refunds';

    public $searchFields = ['order_id' => '订单号'];

    protected $fillable = [
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
    ];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

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
        return $arr[$this->refund];
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
        return $this->hasOne('App\Models\Channel\AccountModel','id','channel_id');
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






































}