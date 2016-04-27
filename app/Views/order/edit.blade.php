@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('order.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="channel_id">渠道类型</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="channel_id" class="form-control" id="channel_id">
                    @foreach($channels as $channel)
                        <option value="{{$channel->id}}" {{$channel->id == $model->channel_id ? 'selected' : ''}}>
                            {{$channel->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="channel_account_id">渠道账号</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="channel_account_id" class="form-control channel_account_id" id="channel_account_id">
                    @foreach($aliases as $alias)
                        <option value="{{$alias->id}}" {{$alias->id == $model->channel_account_id ? 'selected' : ''}}>
                            {{$alias->alias}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="ordernum" class='control-label'>订单号</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="ordernum" placeholder="订单号" name='ordernum' value="{{ old('ordernum') ? old('ordernum') : $model->ordernum }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="channel_ordernum" class='control-label'>渠道订单号</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="channel_ordernum" placeholder="渠道订单号" name='channel_ordernum' value="{{ old('channel_ordernum') ? old('channel_ordernum') : $model->channel_ordernum }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="email" class='control-label'>邮箱</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="email" placeholder="邮箱" name='email' value="{{ old('email') ? old('email') : $model->email }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="status" class='control-label'>订单状态</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="status" id="status">
                    @foreach(config('order.status') as $status_key => $status)
                        <option value="{{ $status_key }}" {{ old('status') ? (old('status') == $status_key ? 'selected' : '') : ($model->status == $status_key ? 'selected' : '') }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="active" class='control-label'>售后状态</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="active" id="active">
                    @foreach(config('order.active') as $active_key => $active)
                        <option value="{{ $active_key }}" {{ old('active') ? (old('active') == $active_key ? 'selected' : '') : ($model->active == $active_key ? 'selected' : '') }}>
                            {{ $active }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="customer_service">客服人员</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="customer_service" class="form-control" id="customer_service">
                    @foreach($users as $user)
                        <option value="{{$user->id}}" {{$user->id == $model->customer_service ? 'selected' : ''}}>
                            {{$user->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="operator">运营人员</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="operator" class="form-control" id="operator">
                    @foreach($users as $user)
                        <option value="{{$user->id}}" {{$user->id == $model->operator ? 'selected' : ''}}>
                            {{$user->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="address_confirm" class='control-label'>地址验证</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="address_confirm" id="address_confirm">
                    @foreach(config('order.address') as $address_key => $address)
                        <option value="{{ $address_key }}" {{ old('address_confirm') ? (old('address_confirm') == $address_key ? 'selected' : '') : ($model->address_confirm == $address_key ? 'selected' : '') }}>
                            {{ $address }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="ip" class='control-label'>IP地址</label>
                <input class="form-control" id="ip" placeholder="IP地址" name='ip' value="{{ old('ip') ? old('ip') : $model->ip }}">
            </div>
            <div class="form-group col-lg-2" id="comment">
                <label for="comment" class='control-label'>备用字段</label>
                <input class="form-control" id="comment" placeholder="备用字段" name='comment' value="{{ old('comment') ? old('comment') : $model->comment }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="comment1" class='control-label'>红人/choies用</label>
                <input class="form-control" id="comment1" placeholder="红人/choies用" name='comment1' value="{{ old('comment1') ? old('comment1') : $model->comment1 }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="remark" class='control-label'>订单备注</label>
                <input class="form-control" id="remark" placeholder="订单备注" name='remark' value="{{ old('remark') ? old('remark') : $model->remark }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="import_remark" class='control-label'>导单备注</label>
                <input class="form-control" id="import_remark" placeholder="导单备注" name='import_remark' value="{{ old('import_remark') ? old('import_remark') : $model->import_remark }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <label for="create_time" class='control-label'>渠道创建时间</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="create_time" placeholder="渠道创建时间" name='create_time' value="{{ old('create_time') ? old('create_time') : $model->create_time }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <label for="affair_time" class='control-label'>做账时间</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="affair_time" placeholder="做账时间" name='affair_time' value="{{ old('affair_time') ? old('affair_time') : $model->affair_time }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <label for="affairer">做账人员</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="affairer" class="form-control" id="affairer">
                    <option value="NULL"></option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}" {{$user->id == $model->affairer ? 'selected' : ''}}>
                            {{$user->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="is_affair" class='control-label'>是否做账</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <div class="radio">
                    <label>
                        <input type="radio" name="is_affair" value="1" {{ old('is_affair') ? (old('is_affair') == "1" ? 'checked' : '') : ($model->is_affair == "1" ? 'checked' : '') }}>是
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="is_affair" value="0" {{ old('is_affair') ? (old('is_affair') == "0" ? 'checked' : '') : ($model->is_affair == "0" ? 'checked' : '') }}>否
                    </label>
                </div>
            </div>
            <div class="form-group col-lg-2">
                <label for="is_partial" class='control-label'>是否分批发货</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <div class="radio">
                    <label>
                        <input type="radio" name="is_partial" value="1" {{ old('is_partial') ? (old('is_partial') == "1" ? 'checked' : '') : ($model->is_partial == "1" ? 'checked' : '') }}>是
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="is_partial" value="0" {{ old('is_partial') ? (old('is_partial') == "0" ? 'checked' : '') : ($model->is_partial == "0" ? 'checked' : '') }}>否
                    </label>
                </div>
            </div>
            <div class="form-group col-lg-2" id="is_multi">
                <label for="is_multi" class='control-label'>是否复数</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="is_multi" placeholder="是否复数" name='is_multi' value="{{ old('is_multi') ? old('is_multi') : $model->is_multi }}">
            </div>
            <div class="form-group col-lg-2" id="hand">
                <label for="by_hand" class='control-label'>是否手工</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <div class="radio">
                    <label>
                        <input type="radio" name="by_hand" value="1" {{ old('by_hand') ? (old('by_hand') == "1" ? 'checked' : '') : ($model->by_hand == "1" ? 'checked' : '') }}>是
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="by_hand" value="0" {{ old('by_hand') ? (old('by_hand') == "0" ? 'checked' : '') : ($model->by_hand == "0" ? 'checked' : '') }}>否
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">支付信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="payment" class='control-label'>支付方式</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="payment" id="payment">
                    @foreach(config('order.payment') as $payment)
                        <option value="{{ $payment }}" {{ old('payment') ? (old('payment') == $payment ? 'selected' : '') : ($model->payment == $payment ? 'selected' : '') }}>
                            {{ $payment }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="currency" class='control-label'>币种</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="currency" id="currency">
                    @foreach(config('order.currency') as $currency)
                        <option value="{{ $currency }}" {{ old('currency') ? (old('currency') == $currency ? 'selected' : '') : ($model->currency == $currency ? 'selected' : '') }}>
                            {{ $currency }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="rate" class='control-label'>汇率</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="rate" placeholder="汇率" name='rate' value="{{ old('rate') ? old('rate') : $model->rate }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount" class='control-label'>总金额</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount" placeholder="总金额" name='amount' value="{{ old('amount') ? old('amount') : $model->amount }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount_product" class='control-label'>产品金额</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount_product" placeholder="产品金额" name='amount_product' value="{{ old('amount_product') ? old('amount_product') : $model->amount_product }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount_shipping" class='control-label'>运费</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount_shipping" placeholder="运费" name='amount_shipping' value="{{ old('amount_shipping') ? old('amount_shipping') : $model->amount_shipping }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount_coupon" class='control-label'>折扣金额</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount_coupon" placeholder="折扣金额" name='amount_coupon' value="{{ old('amount_coupon') ? old('amount_coupon') : $model->amount_coupon }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="transaction_number" class='control-label'>交易号</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="transaction_number" placeholder="交易号" name='transaction_number' value="{{ old('transaction_number') ? old('transaction_number') : $model->transaction_number }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="payment_date" class='control-label'>支付时间</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="payment_date" placeholder="支付时间" name='payment_date' value="{{ old('payment_date') ? old('payment_date') : $model->payment_date }}" readonly>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">物流信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="shipping" class='control-label'>种类</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="shipping" id="shipping">
                    @foreach(config('order.shipping') as $shipping_key => $shipping)
                        <option value="{{ $shipping_key }}" {{ old('shipping') ? (old('shipping') == $shipping_key ? 'selected' : '') : ($model->shipping == $shipping_key ? 'selected' : '') }}>
                            {{ $shipping }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_firstname" class='control-label'>发货名字</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_firstname" placeholder="发货名字" name='shipping_firstname' value="{{ old('shipping_firstname') ? old('shipping_firstname') : $model->shipping_firstname }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_lastname" class='control-label'>发货姓氏</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_lastname" placeholder="发货姓氏" name='shipping_lastname' value="{{ old('shipping_lastname') ? old('shipping_lastname') : $model->shipping_lastname }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_address" class='control-label'>发货地址</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_address" placeholder="发货地址" name='shipping_address' value="{{ old('shipping_address') ? old('shipping_address') : $model->shipping_address }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_address1" class='control-label'>发货地址1</label>
                <input class="form-control" id="shipping_address1" placeholder="发货地址1" name='shipping_address1' value="{{ old('shipping_address1') ? old('shipping_address1') : $model->shipping_address1 }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_city" class='control-label'>发货城市</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_city" placeholder="发货城市" name='shipping_city' value="{{ old('shipping_city') ? old('shipping_city') : $model->shipping_city }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_state" class='control-label'>发货省/州</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_state" placeholder="发货省/州" name='shipping_state' value="{{ old('shipping_state') ? old('shipping_state') : $model->shipping_state }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_country" class='control-label'>发货国家/地区</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_country" placeholder="发货国家/地区" name='shipping_country' value="{{ old('shipping_country') ? old('') : $model->shipping_country }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_zipcode" class='control-label'>发货邮编</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_zipcode" placeholder="发货邮编" name='shipping_zipcode' value="{{ old('shipping_zipcode') ? old('shipping_zipcode') : $model->shipping_zipcode }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_phone" class='control-label'>发货电话</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_phone" placeholder="发货电话" name='shipping_phone' value="{{ old('shipping_phone') ? old('shipping_phone') : $model->shipping_phone }}">
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">账单信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="billing_firstname" class='control-label'>账单名字</label>
                <input class="form-control" id="billing_firstname" placeholder="账单名字" name='billing_firstname' value="{{ old('billing_firstname') ? old('billing_firstname') : $model->billing_firstname }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_lastname" class='control-label'>账单姓氏</label>
                <input class="form-control" id="billing_lastname" placeholder="账单姓氏" name='billing_lastname' value="{{ old('billing_lastname') ? old('billing_lastname') : $model->billing_lastname }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_address" class='control-label'>账单地址</label>
                <input class="form-control" id="billing_address" placeholder="账单地址" name='billing_address' value="{{ old('billing_address') ? old('billing_address') : $model->billing_address }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_city" class='control-label'>账单城市</label>
                <input class="form-control" id="billing_city" placeholder="账单城市" name='billing_city' value="{{ old('billing_city') ? old('billing_city') : $model->billing_city }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_state" class='control-label'>账单省/州</label>
                <input class="form-control" id="billing_state" placeholder="账单省/州" name='billing_state' value="{{ old('billing_state') ? old('billing_state') : $model->billing_state }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_country" class='control-label'>账单国家/地区</label>
                <input class="form-control" id="billing_country" placeholder="账单国家/地区" name='billing_country' value="{{ old('billing_country') ? old('billing_country') : $model->billing_country }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_zipcode" class='control-label'>账单邮编</label>
                <input class="form-control" id="billing_zipcode" placeholder="账单邮编" name='billing_zipcode' value="{{ old('billing_zipcode') ? old('billing_zipcode') : $model->billing_zipcode }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_phone" class='control-label'>账单电话</label>
                <input class="form-control" id="billing_phone" placeholder="账单电话" name='billing_phone' value="{{ old('billing_phone') ? old('billing_phone') : $model->billing_phone }}">
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">退款信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="refund" class='control-label'>退款方式</label>
                <select class="form-control" name="refund" id="refund">
                    @foreach(config('order.payment') as $refund)
                        <option value="{{ $refund }}" {{ old('refund') ? (old('refund') == $refund ? 'selected' : '') : ($model->refund == $refund ? 'selected' : '') }}>
                            {{ $refund }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="refund_currency" class='control-label'>退款币种</label>
                <select class="form-control" name="refund_currency" id="refund_currency">
                    @foreach(config('order.currency') as $refund_currency)
                        <option value="{{ $refund_currency }}" {{ old('refund_currency') ? (old('refund_currency') == $refund_currency ? 'selected' : '') : ($model->refund_currency == $refund_currency ? 'selected' : '') }}>
                            {{ $refund_currency }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="refund_account" class='control-label'>客户账户</label>
                <input class="form-control" id="refund_account" placeholder="客户账户" name='refund_account' value="{{ old('refund_account') ? old('refund_account') : $model->refund_account }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="refund_amount" class='control-label'>退款金额</label>
                <input class="form-control" id="refund_amount" placeholder="退款金额" name='refund_amount' value="{{ old('refund_amount') ? old('refund_amount') : $model->refund_amount }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="refund_time" class='control-label'>退款时间</label>
                <input class="form-control" id="refund_time" placeholder="退款时间" name='refund_time' value="{{ old('refund_time') ? old('refund_time') : $model->refund_time }}" readonly>
            </div>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">产品信息</div>
        <div class="panel-body">
            <div class='row'>
                <div class="form-group col-sm-2">
                    <label for="sku" class='control-label'>sku</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="image" class='control-label'>图片</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="quantity" class='control-label'>数量</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="price" class='control-label'>单价</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="status" class='control-label'>是否有效</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="is_gift" class='control-label'>是否赠品</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="remark" class='control-label'>备注</label>
                </div>
                <div class="form-group col-sm-1">
                    <label for="ship_status" class='control-label'>发货状态</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
            </div>
            @foreach($orderItems as $key => $orderItem)
                <div class='row'>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control sku" id="arr[sku][{{$key}}]" placeholder="sku" name='arr[sku][{{$key}}]' value="{{ old('arr[sku][$key]') ? old('arr[sku][$key]') : $orderItem->sku }}">
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control image" id="arr[image][{{$key}}]" placeholder="图片" name='arr[image][{{$key}}]' value="{{ old('arr[image][$key]') ? old('arr[image][$key]') : $orderItem->image }}">
                    </div>
                    <div class="form-group col-sm-1">
                        <input type='text' class="form-control quantity" id="arr[quantity][{{$key}}]" placeholder="数量" name='arr[quantity][{{$key}}]' value="{{ old('arr[quantity][$key]') ? old('arr[quantity][$key]') : $orderItem->quantity }}">
                    </div>
                    <div class="form-group col-sm-1">
                        <input type='text' class="form-control price" id="arr[price][{{$key}}]" placeholder="单价" name='arr[price][{{$key}}]' value="{{ old('arr[price][$key]') ? old('arr[price][$key]') : $orderItem->price }}">
                    </div>
                    <div class="form-group col-sm-1">
                        <select class="form-control is_active" name="arr[is_active][{{$key}}]" id="arr[is_active][{{$key}}]">
                            @foreach(config('order.is_active') as $is_active_key => $is_active)
                                <option value="{{ $is_active_key }}" {{ old('arr[is_active][$key]') ? (old('arr[is_active][$key]') == $is_active_key ? 'selected' : '') : ($orderItem->is_active == $is_active_key ? 'selected' : '') }}>
                                    {{ $is_active }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-1">
                        <select class="form-control is_gift" name="arr[is_gift][{{$key}}]" id="arr[is_gift][{{$key}}]">
                            @foreach(config('order.whether') as $is_gift_key => $is_gift)
                                <option value="{{ $is_gift_key }}" {{ old('arr[is_gift][$key]') ? (old('arr[is_gift][$key]') == $is_gift_key ? 'selected' : '') : ($orderItem->is_gift == $is_gift_key ? 'selected' : '') }}>
                                    {{ $is_gift }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control remark" id="arr[remark][{{$key}}]" placeholder="备注" name='arr[remark][{{$key}}]' value="{{ old('arr[remark][$key]') ? old('arr[remark][$key]') : $orderItem->remark }}">
                    </div>
                    <div class="form-group col-sm-1">
                        <select class="form-control status" name="arr[status][{{$key}}]" id="arr[status][{{$key}}]">
                            @foreach(config('order.ship_status') as $ship_status_key => $status)
                                <option value="{{ $ship_status_key }}" {{ old('arr[status][$key]') ? (old('arr[status][$key]') == $ship_status_key ? 'selected' : '') : ($orderItem->status == $ship_status_key ? 'selected' : '') }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
                </div>
            @endforeach
        </div>
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        $('#create_time, #payment_date, #affair_time, #refund_time').cxCalendar();

        document.getElementById('comment').style.display='none';
        document.getElementById('is_multi').style.display='none';
        document.getElementById('hand').style.display='none';

        var payment = $('#payment').val();
        $('#refund').val(payment);
        var currency = $('#currency').val();
        $('#refund_currency').val(currency);

        var refund_time = $('#refund_time').val();
        if(refund_time == '0000-00-00') {
            $('#refund').val(null);
            $('#refund_currency').val(null);
            $('#refund_account').val(null);
            $('#refund_amount').val(null);
            $('#refund_time').val(null);
        }

        var affair_time = $('#affair_time').val();
        if(affair_time == '0000-00-00') {
            $('#affair_time').val('');
        }

        var current = 1;
        $('#create_form').click(function(){
            $.ajax({
                url:"{{ route('orderAdd') }}",
                data:{current:current},
                dataType:'html',
                type:'get',
                success:function(result) {
                    $('.addpanel').before(result);
                }
            });
            current++;
        });

        $('#channel_id').click(function(){
            var channel_id = $("#channel_id").val();
            $.ajax({
                url : "{{ route('account') }}",
                data : { id : channel_id },
                dataType : 'json',
                type : 'get',
                success : function(result) {
                    $('.channel_account_id').html();
                    str = '';
                    for(var i=0; i<result.length; i++)
                        str += "<option value='"+result[i]['id']+"'>"+result[i]['alias']+"</option>";
                    $('.channel_account_id').html(str);
                }
            });
        });

        $(document).on('blur', '.sku', function(){
            var tmp = $(this);
            var sku = $(this).val();
            $.ajax({
                url : "{{ route('getMsg') }}",
                data : {sku : sku},
                dataType : 'json',
                type : 'get',
                success : function(result) {
                    if(result != 'sku') {
                        alert('sku有误');
                        tmp.val('');
                        return;
                    }
                }
            });
        });

    });

    $(document).on('click', '.bt_right', function(){
        $(this).parent().remove();
    });

</script>