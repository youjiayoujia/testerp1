@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('logistics.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="short_code" class="control-label">物流方式简码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="short_code" placeholder="物流方式简码" name='short_code' value="{{ old('short_code') ? old('short_code') : $model->short_code }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="logistics_type" class="control-label">物流方式名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="logistics_type" placeholder="物流方式名称" name='logistics_type' value="{{ old('logistics_type') ? old('logistics_type') : $model->logistics_type}}">
        </div>
        <div class="form-group col-lg-2">
            <label for="warehouse_id">仓库</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="warehouse_id" class="form-control" id="warehouse_id">
                @foreach($warehouses as $warehouse)
                    <option value="{{$warehouse->id}}" {{$warehouse->id == $model->warehouse_id ? 'selected' : ''}}>
                        {{$warehouse->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="logistics_supplier_id">物流商</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="logistics_supplier_id" class="form-control" id="logistics_supplier_id">
                @foreach($suppliers as $supplier)
                    <option value="{{$supplier->id}}" {{$supplier->id == $model->logistics_supplier_id ? 'selected' : ''}}>
                        {{$supplier->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="type" class="control-label">物流商物流方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="type" placeholder="物流商物流方式" name='type' value="{{ old('type') ? old('type') : $model->type}}">
        </div>
        <div class="form-group col-lg-2">
            <label for="url" class="control-label">物流追踪网址</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="url" placeholder="物流追踪网址" name='url' value="{{ old('url') ? old('url') : $model->url}}">
        </div>
        <div class="form-group col-lg-2">
            <label for="docking" class="control-label">对接方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="docking" id="docking">
                @foreach(config('logistics.docking') as $docking_key => $docking)
                    <option value="{{ $docking_key }}" {{ old('docking') ? (old('docking') == $docking_key ? 'selected' : '') : ($model->docking == $docking_key ? 'selected' : '') }}>
                        {{ $docking }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="logistics_catalog_id">物流分类</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="logistics_catalog_id" id="logistics_catalog_id">
                <option value="0">==选择物流分类==</option>
                @foreach($catalogs as $catalog)
                    <option value="{{$catalog->id}}" {{ $catalog->id == $model->logistics_catalog_id ? 'selected' : '' }}>
                        {{$catalog->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="logistics_email_template_id">回邮模版</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="logistics_email_template_id" id="logistics_email_template_id">
                <option value="0">==选择回邮模版==</option>
                @foreach($templates as $template)
                    <option value="{{$template->id}}" {{ $template->id == $model->logistics_email_template_id ? 'selected' : '' }}>
                        {{$template->customer}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="species" class="control-label">种类</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="species" value="express" {{ $model->species == 'express' ? 'checked' : '' }}>快递
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="species" value="packet" {{ $model->species == 'packet' ? 'checked' : '' }}>小包
                </label>
            </div>
        </div>
        <div class="form-group col-lg-2">
            <label for="is_enable" class="control-label">是否启用</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="is_enable" value="1" {{ $model->is_enable == '1' ? 'checked' : '' }}>是
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="is_enable" value="0" {{ $model->is_enable == '0' ? 'checked' : '' }}>否
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
                @foreach($selectedLimits as $selectedLimit)
                    <option class="form-control thelimit" value="{{ $selectedLimit->id }}" onclick="deleteLimit( this )">
                        {{ $selectedLimit->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="display:none">
            <textarea class="form-control" rows="3" type="hidden" id="limit" placeholder="物流限制" name='limit' readonly>{{ old('limit') }}</textarea>
        </div>
    </div>
@stop
<script type="text/javascript">
    $(document).ready(function() {
        getPostLimit();
    });

    function getPostLimit(){
        var selectLimit = "";
        $(".thelimit").each(function(){
            selectLimit += $.trim($(this).attr('value')) + ",";
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