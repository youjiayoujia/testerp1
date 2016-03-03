@extends('common.form')


@section('formAction') {{ route('channelAccount.store') }} @stop
@section('formBody')
    <div class="form-group col-lg-2">
        <label for="name" class='control-label'>渠道账号</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="alias" class='control-label'>渠道账号别名</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="alias" name='alias' value="{{ old('alias') }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="domain" class='control-label'>账号对应域名</label>
        <input type='text' class="form-control" id="domain" name='domain' value="{{ old('domain') }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="channel_id" class='control-label'>渠道类型</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select class="form-control" name="channel_id">
            @foreach($channels as $channel)
                <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>{{ $channel->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-2">
        <label for="sync_cycle" class='control-label'>订单同步周期</label>
        <input type='text' class="form-control" id="sync_cycle" name='sync_cycle' value="{{ old('sync_cycle') }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="tracking_config" class="control-label">上传追踪号配置</label>
        <input type='text' class="form-control" id="tracking_config" name='tracking_config' value="{{ old('tracking_config') }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="order_prefix" class="control-label">订单前缀</label>
        <input type='text' class="form-control" id="order_prefix" name='order_prefix' value="{{ old('order_prefix') }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="email" class='control-label'>客服邮箱地址</label>
        <input type='text' class="form-control" id="email" name='email' value="{{ old('email') }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="image_site" class='control-label'>产品图片域名</label>
        <input type='text' class="form-control" id="image_site" name='image_site' value="{{ old('image_site') }}">
    </div>
    <div class="form-group col-lg-3">
        <label for="country_id" class='control-label'>所在国家</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select class="form-control" name="country_id">
            @foreach($countries as $country)
                <option value="{{ $country->id }}" {{ old('channel_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-lg-2">
        <label for="default_businesser_id" class='control-label'>默认运营人员</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select class="form-control select_user" name="default_businesser_id">
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('default_businesser_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-2">
        <label for="default_server_id" class='control-label'>默认客服人员</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select class="form-control" name="default_server_id">
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('default_server_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-3">
        <label for="delivery_warehouse" class='control-label'>默认发货仓库</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select class="form-control" name="delivery_warehouse">
            <option value="本地仓库">本地仓库</option>
            <option value="海外仓库">海外仓库</option>
            <option value="第三方仓库">第三方仓库</option>
        </select>
    </div>


    <div class="form-group col-lg-4" style="clear:left;">
        <label for="activate" class="control-label">是否激活</label>
        <div class="radio">
            <label>
                <input type="radio" name="activate" value="是">是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="activate" value="否" checked>否
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="merge_package" class="control-label">是否相同地址合并包裹</label>
        <div class="radio">
            <label>
                <input type="radio" name="merge_package" value="是">是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="merge_package" value="否" checked>否
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="thanks" class="control-label">是否打印感谢信</label>
        <div class="radio">
            <label>
                <input type="radio" name="thanks" value="是">是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="thanks" value="否" checked>否
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="picking_list" class="control-label">是否打印拣货单</label>
        <div class="radio">
            <label>
                <input type="radio" name="picking_list" value="是">是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="picking_list" value="否" checked>否
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="generate_sku" class="control-label">是否无规则生成渠道SKU</label>
        <div class="radio">
            <label>
                <input type="radio" name="generate_sku" value="是">是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="generate_sku" value="否" checked>否
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="clearance" class="control-label">可否通关</label>
        <div class="radio">
            <label>
                <input type="radio" name="clearance" value="是">是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="clearance" value="否" checked>否
            </label>
        </div>
    </div>

    <div class="form-group col-lg-4" style="clear:left;">
        <label for="country_id" class="control-label">已存在用户</label>
        <select name="country_id" class="form-control" multiple style="height:300px;width:400px;">
            @foreach($users as $user)
                <option class="form-control" value="{{ $user->id }}" onclick="addOption( this )">
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-lg-4" style="">
        <label for="addNewOption" class="control-label">已选运营人员(可多选)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select class="form-control" id="addNewOption" multiple  style="height:300px;width:400px;">
        </select>
    </div>
    <div style="display:none">
        <textarea class="form-control" rows="3" type="hidden" id="businesser_ids" name='businesser_ids' readonly></textarea>
    </div>
@stop

<script type="text/javascript">

    // 拼接已选的option
    function getPostOption(){
        var selectedOptions = "";
        $(".selectedOption").each(function(){
            selectedOptions += $.trim($(this).val()) + ",";
        });
        selectedOptions = selectedOptions.substring(0,selectedOptions.length-1);
        $("#businesser_ids").html(selectedOptions);
    }

    // 检测是否被选
    function checkWhetherSelected(that)
    {
        var selectedOption = [];
        $(".selectedOption").each(function(){
            selectedOption.push($(this).val());
        });

        var status = selectedOption.indexOf($(that).val());
        if(status >= 0){
            return true;
        }else if(status < 0){
            return false;
        }
    }

    function addOption(that){
        if(!checkWhetherSelected(that)){
            var optionHtml = '<option class="form-control selectedOption" value="' + $(that).val() + '" onclick="deleteOption( this )">' + $(that).html() + '</option>';
            $("#addNewOption").append(optionHtml);
            getPostOption();
        }
    }

    function deleteOption(that){
        $(that).remove();
        getPostOption();
    }

</script>