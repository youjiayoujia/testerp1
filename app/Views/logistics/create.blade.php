@extends('common.form')

<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}

@section('formAction') {{ route('logistics.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="code" class="control-label">物流方式简码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="code" placeholder="物流方式简码" name='code' value="{{ old('code') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="name" class="control-label">物流方式名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="物流方式名称" name='name' value="{{ old('name') }}">
        </div>
        <div class="form-group col-lg-2">
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
        <div class="form-group col-lg-2">
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
        <div class="form-group col-lg-2">
            <label for="type" class="control-label">物流商物流方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="type" placeholder="物流商物流方式" name='type' value="{{ old('type') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="url" class="control-label">物流追踪网址</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="url" placeholder="物流追踪网址" name='url' value="{{ old('url') }}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="docking" class="control-label">对接方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="docking" id="docking">
                @foreach(config('logistics.docking') as $docking_key => $docking)
                    <option value="{{ $docking_key }}" {{ old('docking') == $docking_key ? 'selected' : '' }}>
                        {{ $docking }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4">
            <label for="logistics_catalog_id" class="control-label">物流分类</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="logistics_catalog_id" id="logistics_catalog_id">
                <option value="0">==选择物流分类==</option>
                @foreach($catalogs as $catalog)
                    <option value="{{$catalog->id}}" {{ Tool::isSelected('logistics_catalog_id', $catalog->id) }}>
                        {{$catalog->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4">
            <label for="logistics_email_template_id" class="control-label">回邮模版</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="logistics_email_template_id" id="logistics_email_template_id">
                <option value="0">==选择回邮模版==</option>
                @foreach($emailTemplates as $emailTemplate)
                    <option value="{{$emailTemplate->id}}" {{ Tool::isSelected('logistics_email_template_id', $emailTemplate->id) }}>
                        {{$emailTemplate->customer}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class='form-group col-lg-3'>
            <label for="logistics_limits">物流限制</label>
            <select class='form-control logistics_limits' name='logistics_limits[]' multiple>
                <option value=''></option>
                @foreach($limits as $limit)
                    <option value="{{ $limit->id }}">{{$limit->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="logistics_template_id" class="control-label">面单模版</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="logistics_template_id" id="logistics_template_id">
                <option value="0">==选择面单模版==</option>
                @foreach($templates as $template)
                    <option value="{{$template->id}}" {{ Tool::isSelected('logistics_template_id', $template->id) }}>
                        {{$template->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3" id="pool_quantity">
            <label for="pool_quantity" class="control-label">号码池数量</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="pool_quantity" placeholder="号码池数量" name='pool_quantity' value="{{ old('pool_quantity') }}">
        </div>
        <div class="form-group col-lg-3">
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
@stop
<script type='text/javascript'>
    $(document).ready(function () {
        $('.logistics_limits').select2();
    });
</script>