@extends('common.form')

<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}

@section('formAction') {{ route('logistics.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="short_code" class="control-label">物流方式简码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="short_code" placeholder="物流方式简码" name='short_code' value="{{ old('short_code') }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="logistics_type" class="control-label">物流方式名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="logistics_type" placeholder="物流方式名称" name='logistics_type' value="{{ old('logistics_type') }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="warehouse_id">仓库</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="warehouse_id" class="form-control" id="warehouse_id">
                @foreach($warehouses as $warehouse)
                    <option value="{{$warehouse->id}}" {{ Tool::isSelected('warehouse_id', $warehouse->id) }}>
                        {{$warehouse->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4">
            <label for="logistics_supplier_id">物流商</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="logistics_supplier_id" class="form-control" id="logistics_supplier_id">
                @foreach($suppliers as $supplier)
                    <option value="{{$supplier->id}}" {{ Tool::isSelected('logistics_supplier_id', $supplier->id) }}>
                        {{$supplier->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4">
            <label for="type" class="control-label">物流商物流方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="type" placeholder="物流商物流方式" name='type' value="{{ old('type') }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="url" class="control-label">物流追踪网址</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="url" placeholder="物流追踪网址" name='url' value="{{ old('url') }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="docking" class="control-label">对接方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="docking" id="docking">
                @foreach(config('logistics.docking') as $docking)
                    <option value="{{ $docking }}" {{ old('docking') == $docking ? 'selected' : '' }}>
                        {{ $docking }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4">
            <label for="template" class="control-label">面单模版</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="template" id="template">
                @foreach(config('logistics.template') as $template)
                    <option value="{{ $template }}" {{ old('template') == $template ? 'selected' : '' }}>
                        {{ $template }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4">
            <label for="return_address" class="control-label">回邮地址</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="return_address" id="return_address">
                @foreach(config('logistics.return_address') as $return_address)
                    <option value="{{ $return_address }}" {{ old('return_address') == $return_address ? 'selected' : '' }}>
                        {{ $return_address }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4" id="pool_quantity">
            <label for="pool_quantity" class="control-label">号码池数量</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="pool_quantity" placeholder="号码池数量" name='pool_quantity' value="{{ old('pool_quantity') }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="species" class="control-label">种类</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="species" value="express" {{ old('species') ? (old('species') == 'express' ? 'checked' : '') : '' }}>快递
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="species" value="packet" {{ old('species') ? (old('species') == 'packet' ? 'checked' : '') : 'checked' }}>小包
                </label>
            </div>
        </div>
        <div class="form-group col-lg-4">
            <label for="is_enable" class="control-label">是否启用</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="is_enable" value="1">是
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="is_enable" value="0" checked>否
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-4" style="clear:left;">
            <label for="limit" class="control-label">已有限制</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="limit" class="form-control" multiple style="height:300px;width:400px;">
                @foreach($limits as $limit)
                    <option class="form-control" value="{{ $limit->id }}" {{ old('limit') ? old('limit') == $limit->id ? 'selected' : '' : ''}} onclick="addLimit( this )">
                        {{ $limit->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4" style="clear:right;">
            <label for="limit" class="control-label">已选限制</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" id="dselectLimit" multiple  style="height:300px;width:400px;">

            </select>
        </div>
        <div style="display:none">
            <textarea class="form-control" rows="3" type="hidden" id="limit" placeholder="物流限制" name='limit' readonly>{{ old('limit') }}</textarea>
        </div>
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function () {
        //隐藏
        document.getElementById('pool_quantity').style.display='none';
    });

    function getPostLimit(){
        var selectLimit = "";
        $(".thelimit").each(function(){
            selectLimit += $.trim($(this).html()) + ",";
        });
        selectLimit = selectLimit.substring(0,selectLimit.length - 1);
        $("#limit").html(selectLimit);
    }

    // 检测是否被选
    function checkWhetherSelected(that) {
        var selectLimit = [];
        $(".thelimit").each(function () {
            selectLimit.push($(this).val());
        });

        var status = selectLimit.indexOf($(that).val());
        if (status >= 0) {
            return true;
        } else if (status < 0) {
            return false;
        }
    }

    function addLimit(that){
        if(!checkWhetherSelected(that)) {
            var limitHtml = '<option class="form-control thelimit" value="' + $(that).val() + '" onclick="deleteLimit( this )">' + $(that).html() + '</option>';
            $("#dselectLimit").append(limitHtml);
            getPostLimit();
        }
    }

    function deleteLimit(that){
        $(that).remove();
        getPostLimit();
    }

</script>