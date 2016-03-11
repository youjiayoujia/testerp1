@extends('common.form')
@section('formAction') {{ route('order.store') }} @stop
@section('formBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="channel_id">渠道类型</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="channel_id" class="form-control" id="channel_id">
                    @foreach($channels as $channel)
                        <option value="{{$channel->id}}" {{$channel->id == old('$channel->channel->id') ? 'selected' : ''}}>
                            {{$channel->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="channel_account_id">渠道账号</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="channel_account_id" class="form-control channel_account_id" id="channel_account_id">
                    @foreach($accounts as $account)
                        <option value="{{$account->id}}" {{$account->id == old('$account->account->id') ? 'selected' : ''}}>
                            {{$account->alias}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="ordernum" class='control-label'>订单号</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="ordernum" placeholder="订单号" name='ordernum' value="{{ old('ordernum') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="channel_ordernum" class='control-label'>渠道订单号</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="channel_ordernum" placeholder="渠道订单号" name='channel_ordernum' value="{{ old('channel_ordernum') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="email" class='control-label'>邮箱</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="email" placeholder="邮箱" name='email' value="{{ old('email') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="status" class='control-label'>订单状态</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="status" id="status">
                    @foreach(config('order.status') as $status_key => $status)
                        <option value="{{ $status_key }}" {{ old('status') == $status_key ? 'selected' : '' }}>
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
                        <option value="{{ $active_key }}" {{ old('active') == $active_key ? 'selected' : '' }}>
                            {{ $active }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="affairer">做账人员</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="affairer" class="form-control" id="affairer">
                    @foreach($users as $user)
                        <option value="{{$user->id}}" {{$user->id == old('$user->user->id') ? 'selected' : ''}}>
                            {{$user->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="customer_service">客服人员</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="customer_service" class="form-control" id="customer_service">
                    @foreach($users as $user)
                        <option value="{{$user->id}}" {{$user->id == old('$user->user->id') ? 'selected' : ''}}>
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
                        <option value="{{$user->id}}" {{$user->id == old('$user->user->id') ? 'selected' : ''}}>
                            {{$user->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="ip" class='control-label'>IP地址</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="ip" placeholder="IP地址" name='ip' value="{{ old('ip') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="address_confirm" class='control-label'>地址验证</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="address_confirm" id="address_confirm">
                    @foreach(config('order.address') as $address_key => $address)
                        <option value="{{ $address_key }}" {{ old('address_confirm') == $address_key ? 'selected' : '' }}>
                            {{ $address }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="comment" class='control-label'>备用字段</label>
                <input class="form-control" id="comment" placeholder="备用字段" name='comment' value="{{ old('comment') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="comment1" class='control-label'>红人/choies用</label>
                <input class="form-control" id="comment1" placeholder="红人/choies用" name='comment1' value="{{ old('comment1') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="remark" class='control-label'>订单备注</label>
                <input class="form-control" id="remark" placeholder="订单备注" name='remark' value="{{ old('remark') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="import_remark" class='control-label'>导单备注</label>
                <input class="form-control" id="import_remark" placeholder="导单备注" name='import_remark' value="{{ old('import_remark') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="affair_time" class='control-label'>做账时间</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="affair_time" placeholder="做账时间" name='affair_time' value="{{ old('affair_time') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="create_time" class='control-label'>定义时间</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="create_time" placeholder="定义时间" name='create_time' value="{{ old('create_time') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="is_partial" class='control-label'>是否分批发货</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <div class="radio">
                    <label>
                        <input type="radio" name="is_partial" value="1">是
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="is_partial" value="0" checked>否
                    </label>
                </div>
            </div>
            <div class="form-group col-lg-2">
                <label for="by_hand" class='control-label'>是否手工</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <div class="radio">
                    <label>
                        <input type="radio" name="by_hand" value="1" checked>是
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="by_hand" value="0" disabled>否
                    </label>
                </div>
            </div>
            <div class="form-group col-lg-2">
                <label for="is_affair" class='control-label'>是否做账</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <div class="radio">
                    <label>
                        <input type="radio" name="is_affair" value="1">是
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="is_affair" value="0" checked>否
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">金额信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="currency" class='control-label'>币种</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control" name="currency" id="currency">
                    @foreach(config('order.currency') as $currency)
                        <option value="{{ $currency }}" {{ old('currency') == $currency ? 'selected' : '' }}>
                            {{ $currency }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="rate" class='control-label'>汇率</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="rate" placeholder="汇率" name='rate' value="{{ old('rate') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount" class='control-label'>收款金额</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount" placeholder="收款金额" name='amount' value="{{ old('amount') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount_product" class='control-label'>产品金额</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount_product" placeholder="产品金额" name='amount_product' value="{{ old('amount_product') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount_shipping" class='control-label'>订单运费</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount_shipping" placeholder="订单运费" name='amount_shipping' value="{{ old('amount_shipping') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount_coupon" class='control-label'>折扣金额</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount_coupon" placeholder="折扣金额" name='amount_coupon' value="{{ old('amount_coupon') }}">
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
                        <option value="{{ $shipping_key }}" {{ old('shipping') == $shipping_key ? 'selected' : '' }}>
                            {{ $shipping }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_firstname" class='control-label'>发货名字</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_firstname" placeholder="发货名字" name='shipping_firstname' value="{{ old('shipping_firstname') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_lastname" class='control-label'>发货姓氏</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_lastname" placeholder="发货姓氏" name='shipping_lastname' value="{{ old('shipping_lastname') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_address" class='control-label'>发货地址</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_address" placeholder="发货地址" name='shipping_address' value="{{ old('shipping_address') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_address1" class='control-label'>发货地址1</label>
                <input class="form-control" id="shipping_address1" placeholder="发货地址1" name='shipping_address1' value="{{ old('shipping_address1') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_city" class='control-label'>发货城市</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_city" placeholder="发货城市" name='shipping_city' value="{{ old('shipping_city') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_state" class='control-label'>发货省/州</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_state" placeholder="发货省/州" name='shipping_state' value="{{ old('shipping_state') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_country" class='control-label'>发货国家/地区</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_country" placeholder="发货国家/地区" name='shipping_country' value="{{ old('shipping_country') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_zipcode" class='control-label'>发货邮编</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_zipcode" placeholder="发货邮编" name='shipping_zipcode' value="{{ old('shipping_zipcode') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_phone" class='control-label'>发货电话</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_phone" placeholder="发货电话" name='shipping_phone' value="{{ old('shipping_phone') }}">
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
                        <option value="{{ $payment }}" {{ old('payment') == $payment ? 'selected' : '' }}>
                            {{ $payment }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_firstname" class='control-label'>账单名字</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_firstname" placeholder="账单名字" name='billing_firstname' value="{{ old('billing_firstname') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_lastname" class='control-label'>账单姓氏</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_lastname" placeholder="账单姓氏" name='billing_lastname' value="{{ old('billing_lastname') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_address" class='control-label'>账单地址</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_address" placeholder="账单地址" name='billing_address' value="{{ old('billing_address') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_city" class='control-label'>账单城市</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_city" placeholder="账单城市" name='billing_city' value="{{ old('billing_city') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_state" class='control-label'>账单省/州</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_state" placeholder="账单省/州" name='billing_state' value="{{ old('billing_state') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_country" class='control-label'>账单国家/地区</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_country" placeholder="账单国家/地区" name='billing_country' value="{{ old('billing_country') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_zipcode" class='control-label'>账单邮编</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_zipcode" placeholder="账单邮编" name='billing_zipcode' value="{{ old('billing_zipcode') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_phone" class='control-label'>账单电话</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_phone" placeholder="账单电话" name='billing_phone' value="{{ old('billing_phone') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="payment_date" class='control-label'>支付时间</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="payment_date" placeholder="支付时间" name='payment_date' value="{{ old('payment_date') }}">
            </div>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            产品信息
        </div>
        <div class="panel-body" id="itemDiv">
            <div class='row'>
                <div class="form-group col-sm-2">
                    <label for="sku" class='control-label'>sku</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="quantity" class='control-label'>数量</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="price" class='control-label'>金额</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="status" class='control-label'>是否有效</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="ship_status" class='control-label'>发货状态</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="is_gift" class='control-label'>是否赠品</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="remark" class='control-label'>备注</label>
                </div>
            </div>
            <div class='row'>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control sku" id="arr[sku][0]" placeholder="sku" name='arr[sku][0]' value="{{ old('arr[sku][0]') }}">
                </div>
                <div class="form-group col-sm-1">
                    <input type='text' class="form-control quantity" id="arr[quantity][0]" placeholder="数量" name='arr[quantity][0]' value="{{ old('arr[quantity][0]') }}">
                </div>
                <div class="form-group col-sm-1">
                    <input type='text' class="form-control price" id="arr[price][0]" placeholder="金额" name='arr[price][0]' value="{{ old('arr[price][0]') }}">
                </div>
                <div class="form-group col-sm-2">
                    <select class="form-control status" name="arr[status][0]" id="arr[status][0]">
                        @foreach(config('order.product_status') as $product_status_key => $status)
                            <option value="{{ $product_status_key }}" {{ old('arr[status][0]') == $product_status_key ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <select class="form-control ship_status" name="arr[ship_status][0]" id="arr[ship_status][0]">
                        @foreach(config('order.ship_status') as $ship_status_key => $ship_status)
                            <option value="{{ $ship_status_key }}" {{ old('arr[ship_status][0]') == $ship_status_key ? 'selected' : '' }}>
                                {{ $ship_status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control is_gift" name="arr[is_gift][0]" id="arr[is_gift][0]">
                        @foreach(config('order.whether') as $is_gift_key => $is_gift)
                            <option value="{{ $is_gift_key }}" {{ old('arr[is_gift][0]') == $is_gift_key ? 'selected' : '' }}>
                                {{ $is_gift }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control remark" id="arr[remark][0]" placeholder="备注" name='arr[remark][0]' value="{{ old('arr[remark][0]') }}">
                </div>
                <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
            </div>
        </div>
        <div class="panel-footer">
            <div class="create" id="addItem"><i class="glyphicon glyphicon-plus"></i></div>
        </div>
    </div>
@stop
@section('pageJs')
    <script type='text/javascript'>
        $(document).ready(function () {
            $('#create_time, #payment_date, #affair_time').cxCalendar();

            var current = 1;
            $('#addItem').click(function () {
                $.ajax({
                    url: "{{ route('orderAdd') }}",
                    data: {current: current},
                    dataType: 'html',
                    type: 'get',
                    success: function (result) {
                        $('#itemDiv').append(result);
                    }
                });
                current++;
            });

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

            $(document).on('click', '.bt_right', function () {
                $(this).parent().remove();
            });
        });
    </script>
@stop