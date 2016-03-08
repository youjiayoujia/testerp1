@extends('common.form')


@section('formAction') {{ route('channelAccount.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="channel_id" class='control-label'>渠道类型</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="channel_id">
                @foreach($channels as $channel)
                    <option value="{{ $channel->id }}" {{ Tool::isSelected('channel_id', $channel->id) }}>{{ $channel->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="account" class='control-label'>渠道账号</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="account" name='account' value="{{ old('account') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="alias" class='control-label'>渠道账号别名</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="alias" name='alias' value="{{ old('alias') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="country_id" class='control-label'>所在国家</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="country_id">
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ Tool::isSelected('country_id', $country->id) }}>{{ $country->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="warehouse_id" class='control-label'>默认发货仓库</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="warehouse_id">
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ Tool::isSelected('warehouse_id', $warehouse->id) }}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="sync_cycle" class='control-label'>订单同步周期(小时)</label>
            <input type='text' class="form-control" id="sync_cycle" name='sync_cycle' value="{{ old('sync_cycle') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="order_prefix" class="control-label">订单前缀</label>
            <input type='text' class="form-control" id="order_prefix" name='order_prefix' value="{{ old('order_prefix') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="service_email" class='control-label'>客服邮箱地址</label>
            <input type='text' class="form-control" id="service_email" name='service_email' value="{{ old('service_email') }}">
        </div>
    </div>
    <div class="row">

        <div class="form-group col-lg-3">
            <label for="domain" class='control-label'>账号对应域名</label>
            <input type='text' class="form-control" id="domain" name='domain' value="{{ old('domain') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="image_domain" class='control-label'>产品图片域名</label>
            <input type='text' class="form-control" id="image_domain" name='image_domain' value="{{ old('image_domain') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="tracking_config" class="control-label">上传追踪号配置</label>
            <input type='text' class="form-control" id="tracking_config" name='tracking_config' value="{{ old('tracking_config') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="operator_id" class='control-label'>默认运营人员</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control select_user" name="operator_id">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ Tool::isSelected('operator_id', $user->id) }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="customer_service_id" class='control-label'>默认客服人员</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="customer_service_id">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ Tool::isSelected('customer_service_id', $user->id) }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="is_merge_package" class="control-label">是否相同地址合并包裹</label>

            <div class="radio">
                <label>
                    <input type="radio" name="is_merge_package" value="1" {{ Tool::isChecked('is_merge_package', '1', null, true) }}>是
                </label>
                <label>
                    <input type="radio" name="is_merge_package" value="0" {{ Tool::isChecked('is_merge_package', '0') }}>否
                </label>
            </div>
        </div>
        <div class="form-group col-lg-2">
            <label for="is_thanks" class="control-label">是否打印感谢信</label>

            <div class="radio">
                <label>
                    <input type="radio" name="is_thanks" value="1" {{ Tool::isChecked('is_thanks', '1', null, true) }}>是
                </label>
                <label>
                    <input type="radio" name="is_thanks" value="0" {{ Tool::isChecked('is_thanks', '0') }}>否
                </label>
            </div>
        </div>
        <div class="form-group col-lg-2">
            <label for="is_picking_list" class="control-label">是否打印拣货单</label>

            <div class="radio">
                <label>
                    <input type="radio" name="is_picking_list" value="1" {{ Tool::isChecked('is_picking_list', '1', null, true) }}>是
                </label>
                <label>
                    <input type="radio" name="is_picking_list" value="0" {{ Tool::isChecked('is_picking_list', '0') }}>否
                </label>
            </div>
        </div>
        <div class="form-group col-lg-2">
            <label for="is_rand_sku" class="control-label">是否无规则生成渠道SKU</label>

            <div class="radio">
                <label>
                    <input type="radio" name="is_rand_sku" value="1" {{ Tool::isChecked('is_rand_sku', '1', null, true) }}>是
                </label>
                <label>
                    <input type="radio" name="is_rand_sku" value="0" {{ Tool::isChecked('is_rand_sku', '0') }}>否
                </label>
            </div>
        </div>
        <div class="form-group col-lg-2">
            <label for="is_clearance" class="control-label">可否通关</label>

            <div class="radio">
                <label>
                    <input type="radio" name="is_clearance" value="1" {{ Tool::isChecked('is_clearance', '1', null, true) }}>是
                </label>
                <label>
                    <input type="radio" name="is_clearance" value="0" {{ Tool::isChecked('is_clearance', '0') }}>否
                </label>
            </div>
        </div>
        <div class="form-group col-lg-2">
            <label for="is_available" class="control-label">是否激活</label>

            <div class="radio">
                <label>
                    <input type="radio" name="is_available" value="1" {{ Tool::isChecked('is_available', '1', null, true) }}>是
                </label>
                <label>
                    <input type="radio" name="is_available" value="0" {{ Tool::isChecked('is_available', '0') }}>否
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="uses" class="control-label">已存在用户</label>
            <select name="uses" class="form-control" multiple style="height:300px;width:400px;">
                @foreach($users as $user)
                    <option class="form-control" value="{{ $user->id }}" onclick="addOption( this )">
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-lg-4">
            <label for="addNewOption" class="control-label">已选运营人员(可多选)</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" id="addNewOption" multiple style="height:300px;width:400px;">
            </select>
        </div>
        <div style="display:none">
            <textarea class="form-control" rows="3" type="hidden" id="operator_ids" name='operator_ids' readonly></textarea>
        </div>
    </div>
@stop

<script type="text/javascript">

    // 拼接已选的option
    function getPostOption() {
        var selectedOptions = "";
        $(".selectedOption").each(function () {
            selectedOptions += $.trim($(this).val()) + ",";
        });
        selectedOptions = selectedOptions.substring(0, selectedOptions.length - 1);
        $("#operator_ids").html(selectedOptions);
    }

    // 检测是否被选
    function checkWhetherSelected(that) {
        var selectedOption = [];
        $(".selectedOption").each(function () {
            selectedOption.push($(this).val());
        });

        var status = selectedOption.indexOf($(that).val());
        if (status >= 0) {
            return true;
        } else if (status < 0) {
            return false;
        }
    }

    function addOption(that) {
        if (!checkWhetherSelected(that)) {
            var optionHtml = '<option class="form-control selectedOption" value="' + $(that).val() + '" onclick="deleteOption( this )">' + $(that).html() + '</option>';
            $("#addNewOption").append(optionHtml);
            getPostOption();
        }
    }

    function deleteOption(that) {
        $(that).remove();
        getPostOption();
    }

</script>