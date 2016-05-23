@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('logisticsRule.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="type_id">物流方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="type_id" class="form-control" id="type_id">
                @foreach($logisticses as $logistics)
                    <option value="{{$logistics->id}}" {{ Tool::isSelected('type_id', $logistics->id) }}>
                        {{$logistics->type}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="priority" class="control-label">优先级</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="priority" placeholder="优先级" name='priority' value="{{ old('priority') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="weight_from" class="control-label">重量从(kg)</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="weight_from" placeholder="重量从" name='weight_from' value="{{ old('weight_from') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="weight_to" class="control-label">重量至(kg)</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="weight_to" placeholder="重量至" name='weight_to' value="{{ old('weight_to') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="order_amount" class="control-label">订单金额($)</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="order_amount" placeholder="订单金额" name='order_amount' value="{{ old('order_amount') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="is_clearance">是否通关</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="is_clearance" value="1" {{old('is_clearance') ? (old('is_clearance') == '1' ? 'checked' : '') : 'checked'}}>是
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="is_clearance" value="0" {{old('is_clearance') ? (old('is_clearance') == '0' ? 'checked' : '') : ''}}>否
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-4" style="clear:left;">
            <label for="country" class="control-label">已有国家</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <button type="button" class="btn btn-success btn-xs" onclick="quanxuan()">全选</button>
            <select name="country" class="form-control" multiple style="height:300px;width:400px;">
                @foreach($countries as $country)
                    <option class="form-control" value="{{ $country->code }}" {{ old('country') ? old('country') == $country->code ? 'selected' : '' : ''}} onclick="addCountry( this )">
                        {{ $country->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4" style="clear:right;">
            <label for="country" class="control-label">已选国家</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" id="dselectCountry" multiple  style="height:300px;width:400px;">

            </select>
        </div>
        <div style="display:none">
            <textarea class="form-control" rows="3" type="hidden" id="country" placeholder="国家" name='country' readonly>{{ old('country') }}</textarea>
        </div>
    </div>
@stop
<script type="text/javascript">
    function getPostCountry(){
        var selectCountry = "";
        $(".thecountry").each(function(){
            selectCountry += $.trim($(this).attr('value')) + ",";
        });
        selectCountry = selectCountry.substring(0,selectCountry.length - 1);
        $("#country").html(selectCountry);
    }

    // 检测是否被选
    function checkWhetherSelected(that) {
        var selectCountry = [];
        $(".thecountry").each(function () {
            selectCountry.push($(this).val());
        });

        var status = selectCountry.indexOf($(that).val());
        if (status >= 0) {
            return true;
        } else if (status < 0) {
            return false;
        }
    }

    function addCountry(that){
        if(!checkWhetherSelected(that)) {
            var countryHtml = '<option class="form-control thecountry" value="' + $(that).val() + '" onclick="deleteCountry( this )">' + $(that).html() + '</option>';
            $("#dselectCountry").append(countryHtml);
            getPostCountry();
        }
    }

    function deleteCountry(that){
        $(that).remove();
        getPostCountry();
    }

    //全选
    function quanxuan(that)
    {
        var checkCountries = '@foreach($countries as $country)' +
                '<option class="form-control thecountry" value="{{ $country->code }}" onclick="deleteCountry(this)">' +
                '{{ $country->name }}' + '</option>' + '@endforeach';
        $("#dselectCountry").append(checkCountries);
        getPostCountry();
    }

</script>