@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('logisticsRule.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="type_id">仓库</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="type_id" class="form-control" id="type_id">
                @foreach($logisticses as $logisticse)
                    <option value="{{$logisticse->id}}" {{$logisticse->id == $model->type_id ? 'selected' : ''}}>
                        {{$logisticse->type}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="priority" class="control-label">优先级</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="priority" placeholder="优先级" name='priority' value="{{ old('priority') ? old('priority') : $model->priority }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="weight_from" class="control-label">重量从(kg)</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="weight_from" placeholder="重量从" name='weight_from' value="{{ old('weight_from') ? old('weight_from') : $model->weight_from }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="weight_to" class="control-label">重量至(kg)</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="weight_to" placeholder="重量至" name='weight_to' value="{{ old('weight_to') ? old('weight_to') : $model->weight_to }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="order_amount" class="control-label">订单金额($)</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="order_amount" placeholder="订单金额" name='order_amount' value="{{ old('order_amount') ? old('order_amount') : $model->order_amount }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="is_clearance">是否通关</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="is_clearance" value="1" {{old('is_clearance') ? (old('is_clearance') == '1' ? 'checked' : '') : ($model->is_clearance == '1' ? 'checked' : '')}}>是
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="is_clearance" value="0" {{old('is_clearance') ? (old('is_clearance') == '0' ? 'checked' : '') : ($model->is_clearance == '0' ? 'checked' : '')}}>否
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-2" style="clear:left;">
            <label for="country" class="control-label">已有国家</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="country" class="form-control" multiple style="height:250px;width:180px;">
                @foreach($countries as $country)
                    <option class="form-control" value="{{ $country->id }}" {{ old('country') ? old('country') == $country->id ? 'selected' : '' : ''}} onclick="addCountry( this )">
                        {{ $country->abbreviation }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2" style="clear:right;">
            <label for="country" class="control-label">已选国家</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" id="dselectCountry" multiple style="height:250px;width:180px;">
                @foreach($selectedCountries as $selectedCountry)
                    <option class="form-control thecountry" value="{{ $selectedCountry }}" onclick="deleteCountry( this )">
                        {{ $selectedCountry }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="display:none">
            <textarea class="form-control" rows="3" type="hidden" id="country" placeholder="国家" name='country' readonly>{{ old('country') }}</textarea>
        </div>
    </div>
@stop
<script type="text/javascript">
    $(document).ready(function() {
        getPostCountry();
    });

    function getPostCountry(){
        var selectCountry = "";
        $(".thecountry").each(function(){
            selectCountry += $.trim($(this).html()) + ",";
        });
        selectCountry=selectCountry.substring(0,selectCountry.length-1);
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
</script>