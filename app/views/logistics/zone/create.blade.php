@extends('common.form')

<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}

@section('formAction') {{ route('logisticsZone.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="form-group col-lg-4">
        <label for="zone" class="control-label">物流分区</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="zone" placeholder="命名建议:shipping+数字(1区取1,2区取2,其他区取99)" name="zone" value="{{ old('zone') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="logistics_id">物流方式</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="logistics_id" class="form-control" id="logistics_id" onclick="checkShipping()">
            @foreach($logistics as $logisticses)
                <option value="{{ $logisticses->id }}" {{ old('logistics_id') ? old('logistics_id') == $logisticses->id ? 'selected' : '' : ''}}>
                    {{ $logisticses->logistics_type }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label for="shipping_id" class="control-label">种类</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping_id" placeholder="种类" name='shipping_id' value="{{ old('shipping_id') }}" readonly>
    </div>
    <div class="form-group col-lg-4" id="express">
        <label for="fixed_weight" class="control-label">首重(kg)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="fixed_weight" placeholder="首重(kg)" name='fixed_weight' value="{{ old('fixed_weight') }}">
    </div>
    <div class="form-group col-lg-4" id="express">
        <label for="fixed_price" class="control-label">首重价格(/kg)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="fixed_price" placeholder="首重价格(/kg)" name='fixed_price' value="{{ old('fixed_price') }}">
    </div>
    <div class="form-group col-lg-4" id="express">
        <label for="continued_weight" class="control-label">续重(kg)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="continued_weight" placeholder="续重(kg)" name='continued_weight' value="{{ old('continued_weight') }}">
    </div>
    <div class="form-group col-lg-4" id="express">
        <label for="continued_price" class="control-label">续重价格(/kg)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="continued_price" placeholder="续重价格(/kg)" name='continued_price' value="{{ old('continued_price') }}">
    </div>
    <div class="form-group col-lg-4" id="express">
        <label for="other_fixed_price" class="control-label">其他固定费用</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="other_fixed_price" placeholder="其他固定费用" name='other_fixed_price' value="{{ old('other_fixed_price') }}">
    </div>
    <div class="form-group col-lg-4" id="express">
        <label for="other_scale_price" class="control-label">其他比例费用(%)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="other_scale_price" placeholder="其他比例费用(%)" name='other_scale_price' value="{{ old('other_scale_price') }}">
    </div>
    <div class="form-group col-lg-4" id="packet">
        <label for="price" class="control-label">价格(/kg)</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="price" placeholder="价格(/kg)" name='price' value="{{ old('price') }}">
    </div>
    <div class="form-group col-lg-4" id="packet">
        <label for="other_price" class="control-label">其他费用</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="other_price" placeholder="其他费用" name='other_price' value="{{ old('other_price') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="discount" class="control-label">最后折扣</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="discount" placeholder="最后折扣(八折录入0.8)" name='discount' value="{{ old('discount') }}">
    </div>
    <div class="form-group col-lg-12">
        <label for="country_id" class="control-label">国家</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="country_id" class="form-control" id="country_id" multiple onclick="fun()">
            @foreach($country as $countries)
                <option class="form-control" value="{{ $countries->id }}" {{ old('country_id') ? old('country_id') == $countries->id ? 'selected' : '' : ''}}>
                    {{ $countries->name }}
                </option>
            @endforeach
        </select>
        <textarea class="form-control" rows="3" id="country_id" placeholder="国家" name='country_id' readonly>{{ old('country_id') }}</textarea>
    </div>
@stop
<script type="text/javascript">
//    $(function(){
//        $("select[name = 'country_id']").click(function() {
//            var all = "";
//            $("select option").each(function() {
//                all += $(this).attr("value")+",";
//            });
//            var sel = $("select[name = 'country_id']").val();
//            alert("多选列表所有的value值:"+all);
//            alert("其中被选中的是:"+sel);
//        });
//    });

    $(document).ready(function() {
        var logistics_id = $("#logistics_id").val();
        $.ajax({
            url : "{{ route('zone') }}",
            data : { id : logistics_id },
            dataType : 'json',
            type : 'get',
            success : function(result) {
                $('#shipping_id').val(result);
                if (result == 'express') {
                    $("div#express").show();
                    $("div#packet").hide();
                }else {
                    $("div#packet").show();
                    $("div#express").hide();
                }
            }
        });
    });

    function checkShipping() {
        var logistics_id = $("#logistics_id").val();
        $.ajax({
            url : "{{ route('zone') }}",
            data : { id : logistics_id },
            dataType : 'json',
            type : 'get',
            success : function(result) {
                $('#shipping_id').val(result);
                if (result == 'express') {
                    $('div#express').show();
                    $('div#packet').hide();
                }else {
                    $('div#packet').show();
                    $('div#express').hide();
                }
            }
        });
    }

    function selectCountry() {
        var country_id = $("#country_id").val();
        $.ajax({
            url : "{{ route('country') }}",
            data : { id : country_id},
            dataType : 'json',
            type : 'get',
            success : function(result) {
                $("textarea[name = 'country_id']").val(result);
            }
        });
    }

    function fun() {
        var select = document.getElementById("country_id");
        //var str = [];
        var arr = [];
        for(i = 0; i < select.length; i++) {
            if(select.options[i].selected) {
                //str.push(select[i].value);
                $.ajax({
                    url : "{{ route('country') }}",
                    data : { id : select[i].value},
                    dataType : 'json',
                    type : 'get',
                    success : function(result) {
                        arr += result + ",";
                        $("textarea[name = 'country_id']").val(arr);
                    }
                });
            }
        }
    }
</script>
