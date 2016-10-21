@extends('common.form')
@section('formAction') {{ route('countriesSort.store') }} @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-6">
            <label for="name" class='control-label'>分类名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="分类名称" name='name' value="{{ old('name') }}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-4" style="clear:left;">
            <label for="country_id" class="control-label">已有国家</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <button type="button" class="btn btn-success btn-xs" onclick="quanxuan()">全选</button>
            <select name="country_id" class="form-control" multiple style="height:300px;width:400px;">
                @foreach($countries as $country)
                    <option class="form-control" value="{{ $country->id }}" {{ old('country_id') ? old('country_id') == $country->id ? 'selected' : '' : ''}} onclick="addCountry( this )">
                        {{ $country->cn_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4" style="clear:right;">
            <label for="country_id" class="control-label">已选国家</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" id="dselectCountry" multiple  style="height:300px;width:400px;">

            </select>
        </div>
        <div style="display:none">
            <textarea class="form-control" rows="3" type="hidden" id="country_id" placeholder="国家" name='country_id' readonly>{{ old('country_id') }}</textarea>
        </div>
    </div>
@stop
@section('pageJs')
<script type="text/javascript">
    function getPostCountry(){
        var selectCountry = "";
        $(".thecountry").each(function(){
            selectCountry += $.trim($(this).attr('value')) + ",";
        });
        selectCountry = selectCountry.substring(0,selectCountry.length - 1);
        $("#country_id").html(selectCountry);
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
                '<option class="form-control thecountry" value="{{ $country->id }}" onclick="deleteCountry(this)">' +
                '{{ $country->name }}' + '</option>' + '@endforeach';
        $("#dselectCountry").append(checkCountries);
        getPostCountry();
    }

</script>

@stop