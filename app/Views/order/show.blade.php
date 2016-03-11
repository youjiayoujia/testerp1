@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>渠道</strong>: {{ $model->channel->name }}
            </div>
            <div class="col-lg-2">
                <strong>渠道账号</strong>: {{ $model->channelAccount->alias }}
            </div>
            <div class="col-lg-2">
                <strong>订单号</strong>: {{ $model->ordernum }}
            </div>
            <div class="col-lg-2">
                <strong>渠道订单号</strong>: {{ $model->channel_ordernum }}
            </div>
            <div class="col-lg-2">
                <strong>邮箱</strong>: {{ $model->email }}
            </div>
            <div class="col-lg-2">
                <strong>订单状态</strong>: {{ $model->status_name }}
            </div>
            <div class="col-lg-2">
                <strong>售后状态</strong>: {{ $model->active_name }}
            </div>
            <div class="col-lg-2">
                <strong>做账人员</strong>: {{ $model->user_affairer->name }}
            </div>
            <div class="col-lg-2">
                <strong>客服人员</strong>: {{ $model->user_service->name }}
            </div>
            <div class="col-lg-2">
                <strong>运营人员</strong>: {{ $model->user_operator->name }}
            </div>
            <div class="col-lg-2">
                <strong>IP地址</strong>: {{ $model->ip }}
            </div>
            <div class="col-lg-2">
                <strong>地址验证</strong>: {{ $model->address_confirm_name }}
            </div>
            <div class="col-lg-2">
                <strong>备用字段</strong>: {{ $model->comment }}
            </div>
            <div class="col-lg-2">
                <strong>红人/choies用</strong>: {{ $model->comment1 }}
            </div>
            <div class="col-lg-2">
                <strong>订单备注</strong>: {{ $model->remark }}
            </div>
            <div class="col-lg-2">
                <strong>导单备注</strong>: {{ $model->import_remark }}
            </div>
            <div class="col-lg-2">
                <strong>是否分批发货</strong>: {{ $model->is_partial_name }}
            </div>
            <div class="col-lg-2">
                <strong>是否手工</strong>: {{ $model->by_hand_name }}
            </div>
            <div class="col-lg-2">
                <strong>是否做账</strong>: {{ $model->is_affair_name }}
            </div>
            <div class="col-lg-2">
                <strong>做账时间</strong>: {{ $model->affair_time }}
            </div>
            <div class="col-lg-2">
                <strong>定义时间</strong>: {{ $model->create_time }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">金额信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>币种</strong>: {{ $model->currency }}
            </div>
            <div class="col-lg-2">
                <strong>汇率</strong>: {{ $model->rate }}
            </div>
            <div class="col-lg-2">
                <strong>收款金额</strong>: {{ $model->amount }}
            </div>
            <div class="col-lg-2">
                <strong>产品金额</strong>: {{ $model->amount_product }}
            </div>
            <div class="col-lg-2">
                <strong>订单运费</strong>: {{ $model->amount_shipping }}
            </div>
            <div class="col-lg-2">
                <strong>折扣金额</strong>: {{ $model->amount_coupon }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">物流信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>种类</strong>: {{ $model->shipping == 'packet' ? '小包' : '快递' }}
            </div>
            <div class="col-lg-2">
                <strong>发货名字</strong>: {{ $model->shipping_firstname }}
            </div>
            <div class="col-lg-2">
                <strong>发货姓氏</strong>: {{ $model->shipping_lastname }}
            </div>
            <div class="col-lg-2">
                <strong>发货地址</strong>: {{ $model->shipping_address }}
            </div>
            <div class="col-lg-2">
                <strong>发货地址1</strong>: {{ $model->shipping_address1 }}
            </div>
            <div class="col-lg-2">
                <strong>发货城市</strong>: {{ $model->shipping_city }}
            </div>
            <div class="col-lg-2">
                <strong>发货省/州</strong>: {{ $model->shipping_state }}
            </div>
            <div class="col-lg-2">
                <strong>发货国家/地区</strong>: {{ $model->shipping_country }}
            </div>
            <div class="col-lg-2">
                <strong>发货邮编</strong>: {{ $model->shipping_zipcode }}
            </div>
            <div class="col-lg-2">
                <strong>发货电话</strong>: {{ $model->shipping_phone }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">支付信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>支付方式</strong>: {{ $model->payment }}
            </div>
            <div class="col-lg-2">
                <strong>账单名字</strong>: {{ $model->billing_firstname }}
            </div>
            <div class="col-lg-2">
                <strong>账单姓氏</strong>: {{ $model->billing_lastname }}
            </div>
            <div class="col-lg-2">
                <strong>账单地址</strong>: {{ $model->billing_address }}
            </div>
            <div class="col-lg-2">
                <strong>账单城市</strong>: {{ $model->billing_city }}
            </div>
            <div class="col-lg-2">
                <strong>账单省/州</strong>: {{ $model->billing_state }}
            </div>
            <div class="col-lg-2">
                <strong>账单国家/地区</strong>: {{ $model->billing_country }}
            </div>
            <div class="col-lg-2">
                <strong>账单邮编</strong>: {{ $model->billing_zipcode }}
            </div>
            <div class="col-lg-2">
                <strong>账单电话</strong>: {{ $model->billing_phone }}
            </div>
            <div class="col-lg-2">
                <strong>支付时间</strong>: {{ $model->payment_date }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">产品信息</div>
        <div class="panel-body">
            @foreach($orderItems as $orderItem)
                <div class="row">
                    <div class="col-lg-2">
                        <strong>sku</strong> : {{ $orderItem->sku }}
                    </div>
                    <div class="col-lg-2">
                        <strong>数量</strong> : {{ $orderItem->quantity }}
                    </div>
                    <div class="col-lg-2">
                        <strong>金额</strong> : {{ $orderItem->price }}
                    </div>
                    <div class="col-lg-2">
                        <strong>发货状态</strong> : {{ $orderItem->ship_status_name }}
                    </div>
                    <div class="col-lg-2">
                        <strong>是否有效</strong> : {{ $orderItem->status_name }}
                    </div>
                    <div class="col-lg-2">
                        <strong>是否赠品</strong> : {{ $orderItem->is_gift_name }}
                    </div>
                    <div class="col-lg-2">
                        <strong>备注</strong> : {{ $orderItem->remark }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop