@extends('common.form')

<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}

@section('formAction') {{ route('logisticsZone.update', ['id' => $zone->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-4">
        <label for="zone" class="control-label">物流分区</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="zone" placeholder="命名建议:shipping+数字(1区取1,2区取2,其他区取99)" name="zone" value="{{ old('zone') ? old('zone') : $zone->zone }}" readonly>
    </div>
    <div class="form-group col-lg-4">
        <label for="logistics_id" class="control-label">物流方式</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="logistics_id" class="form-control" id="logistics_id">
            <option value="{{$zone->logistics_id}}">
                {{$zone->logistics->logistics_type}}
            </option>
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label for="country_id" class="control-label">国家</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="country_id" class="form-control" id="country_id">
            <option value="{{$zone->country_id}}">
                {{$zone->country->name}}
            </option>
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label for="shipping_id" class="control-label">种类</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping_id" placeholder="种类" name="shipping_id" value="{{ old('shipping_id') ? old('shipping_id') : $zone->shipping_id }}" readonly>
    </div>
    <div class="form-group col-lg-4" id="express">
        <label for="fixed_weight" class="control-label">首重(kg)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="fixed_weight" placeholder="首重(kg)" name='fixed_weight' value="{{ old('fixed_weight') ? old('fixed_weight') : $zone->fixed_weight }}">
    </div>
    <div class="form-group col-lg-4" id="express">
        <label for="fixed_price" class="control-label">首重价格(/kg)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="fixed_price" placeholder="首重价格(/kg)" name='fixed_price' value="{{ old('fixed_price') ? old('fixed_price') : $zone->fixed_price }}">
    </div>
    <div class="form-group col-lg-4" id="express">
        <label for="continued_weight" class="control-label">续重(kg)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="continued_weight" placeholder="续重(kg)" name='continued_weight' value="{{ old('continued_weight') ? old('continued_weight') : $zone->continued_weight }}">
    </div>
    <div class="form-group col-lg-4" id="express">
        <label for="continued_price" class="control-label">续重价格(/kg)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="continued_price" placeholder="续重价格(/kg)" name='continued_price' value="{{ old('continued_price') ? old('continued_price') : $zone->continued_price }}">
    </div>
    <div class="form-group col-lg-4" id="express">
        <label for="other_fixed_price" class="control-label">其他固定费用</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="other_fixed_price" placeholder="其他固定费用" name='other_fixed_price' value="{{ old('other_fixed_price') ? old('other_fixed_price') : $zone->other_fixed_price }}">
    </div>
    <div class="form-group col-lg-4" id="express">
        <label for="other_scale_price" class="control-label">其他比例费用(%)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="other_scale_price" placeholder="其他比例费用(%)" name='other_scale_price' value="{{ old('other_scale_price') ? old('other_scale_price') : $zone->other_scale_price }}">
    </div>
    <div class="form-group col-lg-4" id="packet">
        <label for="price" class="control-label">价格(/kg)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="price" placeholder="价格(/kg)" name='price' value="{{ old('price') ? old('price') : $zone->price }}">
    </div>
    <div class="form-group col-lg-4" id="packet">
        <label for="other_price" class="control-label">其他费用</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="other_price" placeholder="其他费用" name='other_price' value="{{ old('other_price') ? old('other_price') : $zone->other_price }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="discount" class="control-label">最后折扣</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="discount" placeholder="最后折扣(八折录入0.8)" name='discount' value="{{ old('discount') ? old('discount') : $zone->discount }}">
    </div>
@stop
<script type="text/javascript">
    $(document).ready(function() {
        var shipping_id = $("#shipping_id").val();
        if (shipping_id == 'express') {
            $("div#express").show();
            $("div#packet").hide();
        }else {
            $("div#packet").show();
            $("div#express").hide();
        }
    });
</script>