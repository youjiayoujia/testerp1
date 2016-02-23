@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('channelAccount.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-2">
        <label for="account" class='control-label'>渠道账号</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" name='name' value="{{ $model->name }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="account" class='control-label'>渠道账号别名</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="alias" name='alias' value="{{ $model->alias }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="account" class='control-label'>账号对应域名</label>
        <input type='text' class="form-control" id="domain" name='domain' value="{{ $model->domain }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="type" class='control-label'>渠道类型</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select class="form-control" name="channel_id">
            @foreach($channels as $channel)
                <option value="{{ $channel->id }}" {{ $model->channel_id == $channel->id ? 'selected' : '' }}>{{ $channel->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-2">
        <label for="account" class='control-label'>订单同步周期</label>
        <input type='text' class="form-control" id="sync_cycle" name='sync_cycle' value="{{ $model->sync_cycle }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="is_enable" class="control-label">上传追踪号配置</label>
        <input type='text' class="form-control" id="tracking_config" name='tracking_config' value="{{ $model->tracking_config }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="is_enable" class="control-label">订单前缀</label>
        <input type='text' class="form-control" id="order_prefix" name='order_prefix' value="{{ $model->order_prefix }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="account" class='control-label'>客服邮箱地址</label>
        <input type='text' class="form-control" id="email" name='email' value="{{ $model->email }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="account" class='control-label'>产品图片域名</label>
        <input type='text' class="form-control" id="image_site" name='email' value="{{ $model->image_site }}">
    </div>

    <div class="form-group col-lg-3">
        <label for="type" class='control-label'>所在国家</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select class="form-control" name="country_id">
            @foreach($countries as $country)
                <option value="{{ $country->id }}" {{ $model->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-2">
        <label for="type" class='control-label'>默认运营人员</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select class="form-control" name="default_businesser_id">
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $model->default_businesser_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-2">
        <label for="type" class='control-label'>默认客服人员</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select class="form-control" name="default_server_id">
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $model->default_server_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-3">
        <label for="type" class='control-label'>默认发货仓库</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select class="form-control" name="delivery_warehouse">
            <option value="本地仓库" {{ $model->delivery_warehouse == '本地仓库' ? 'selected' : '' }}>本地仓库</option>
            <option value="海外仓库" {{ $model->delivery_warehouse == '海外仓库' ? 'selected' : '' }}>海外仓库</option>
            <option value="第三方仓库" {{ $model->delivery_warehouse == '第三方仓库' ? 'selected' : '' }}>第三方仓库</option>
        </select>
    </div>
    <div class="form-group col-lg-4" style="clear:left;">
        <label for="is_enable" class="control-label">是否激活</label>
        <div class="radio">
            <label>
                <input type="radio" name="activate" value="是" {{ $model->activate == '是' ? 'checked' : '' }}>是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="activate" value="否" {{ $model->activate == '否' ? 'checked' : '' }}>否
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="is_enable" class="control-label">是否相同地址合并包裹</label>
        <div class="radio">
            <label>
                <input type="radio" name="merge_package" value="是" {{ $model->merge_package == '是' ? 'checked' : '' }}>是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="merge_package" value="否"  {{ $model->merge_package == '否' ? 'checked' : '' }}>否
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="is_enable" class="control-label">是否打印感谢信</label>
        <div class="radio">
            <label>
                <input type="radio" name="thanks" value="是" {{ $model->thanks == '是' ? 'checked' : '' }}>是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="thanks" value="否" {{ $model->thanks == '否' ? 'checked' : '' }}>否
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="is_enable" class="control-label">是否打印拣货单</label>
        <div class="radio">
            <label>
                <input type="radio" name="picking_list" value="是" {{ $model->picking_list == '是' ? 'checked' : '' }}>是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="picking_list" value="否" {{ $model->picking_list == '否' ? 'checked' : '' }}>否
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="is_enable" class="control-label">是否无规则生成渠道SKU</label>
        <div class="radio">
            <label>
                <input type="radio" name="generate_sku" value="是" {{ $model->generate_sku == '是' ? 'checked' : '' }}>是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="generate_sku" value="否" {{ $model->generate_sku == '否' ? 'checked' : '' }}>否
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="is_enable" class="control-label">可否通关</label>
        <div class="radio">
            <label>
                <input type="radio" name="clearance" value="是" {{ $model->clearance == '是' ? 'checked' : '' }}>是
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="clearance" value="否" {{ $model->clearance == '否' ? 'checked' : '' }}>否
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
        <label for="country_id" class="control-label">已选运营人员(可多选)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select class="form-control" id="addNewOption" multiple  style="height:300px;width:400px;">
            @foreach($model->businessers as $businesser)
                <option class="form-control selectedOption" value="{{ $businesser->id  }}" onclick="deleteOption( this )">{{ $businesser->name }}</option>
            @endforeach
        </select>
    </div>
    <div style="display:none;">
        <textarea class="form-control" rows="3" type="hidden" id="businesser_ids" name='businesser_ids' readonly></textarea>
    </div>
@stop

<script type="text/javascript">

    $(document).ready(function() {
        getPostOption();
    });

    function getPostOption(){
        var selectedOptions = "";
        $(".selectedOption").each(function(){
            selectedOptions += $.trim($(this).val()) + ",";
        });
        selectedOptions = selectedOptions.substring(0,selectedOptions.length-1);
        $("#businesser_ids").html(selectedOptions);
    }

    function addOption(that){
        if(!$(that).hasClass("selected")){
            $(that).addClass("selected");
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