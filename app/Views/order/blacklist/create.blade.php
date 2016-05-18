@extends('common.form')
@section('formAction') {{ route('orderBlacklist.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="channel_id" class="control-label">平台</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="channel_id" placeholder="平台" name='channel_id' value="{{ old('channel_id') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="ordernum" class="control-label">内单号</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="ordernum" placeholder="内单号" name='ordernum' value="{{ old('ordernum') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="name" class="control-label">收货人姓名</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="收货人姓名" name='name' value="{{ old('name') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="email" class="control-label">邮箱</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="email" placeholder="邮箱" name='email' value="{{ old('email') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="zipcode" class="control-label">收货人邮编</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="zipcode" placeholder="收货人邮编" name='zipcode' value="{{ old('zipcode') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="refund_number" class="control-label">退款订单数</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="refund_number" placeholder="退款订单数" name='refund_number' value="{{ old('refund_number') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="total_order" class="control-label">客户总订单数</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="total_order" placeholder="客户总订单数" name='total_order' value="{{ old('total_order') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="remark" class="control-label">备注</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="whitelist">纳入白名单</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="whitelist" value="1" {{old('whitelist') ? (old('whitelist') == '1' ? 'checked' : '') : 'checked'}}>是
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="whitelist" value="0" checked {{old('whitelist') ? (old('whitelist') == '0' ? 'checked' : '') : 'checked'}}>否
                </label>
            </div>
        </div>
    </div>
@stop