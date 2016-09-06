<?php
/**
 * 订单退款模型
 *
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

    public $searchFields = ['order_id', 'refund_amount', 'refund_currency'];

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
        'image'
    ];

    public function getReasonNameAttribute()
    {
        $arr = config('order.reason');
        return $arr[$this->reason];
    }

    public function getTypeNameAttribute()
    {
        $arr = config('order.type');
        return $arr[$this->type];
    }

    public function getRefundNameAttribute()
    {
        $arr = config('order.refund');
        return $arr[$this->refund];
    }
    public function getProcessStatusNameAttribute(){
        return config('refund.process')[$this->process_status];
    }

    public function Order(){
        return $this->hasOne('App\Models\OrderModel','id','order_id');
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

}