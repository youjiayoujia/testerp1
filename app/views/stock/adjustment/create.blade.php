@extends('common.form')
@section('title') 添加库存调整信息 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('stockAdjustment.index') }}">库存调整</a></li>
        <li class="active"><strong>添加库存调整信息</strong></li>
    </ol>
@stop
    <link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formTitle') 添加库存调整信息 @stop
@section('formAction') {{ route('stockAdjustment.store') }} @stop
@section('formBody')
    <div class='form-group'>
        <label for='adjust_form_id'>调整单号</label>
        <input type='text' class='form-control' name='adjust_form_id' id='adjust_form_id' value="{{ old('adjust_form_id') ? old('adjust_form_id') : 'CD'.time() }}" readonly>
    </div>
    <div class="form-group">
        <label for="warehouses_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name='warehouses_id' id='warehouses_id' class='form-control'>
            <option>请选择仓库</option>
            @foreach($warehouses as $warehouse)
                <option value={{ $warehouse->id }} {{ old('warehouses_id') ? old('warehouses_id') == $warehouse->id ? 'selected' : '' : ''}}>{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>
    <div class='row'>
        <div class="form-group col-sm-6">
            <label for="adjust_man_id">调整人</label>
            <input type='text' class="form-control" id="adjust_man_id" placeholder="调整人" name='adjust_man_id' value="{{ old('adjust_man_id') }}" readonly>
        </div>
        <div class="form-group col-sm-6">
            <label for="adjust_time">调整时间</label>
            <input type='text' class="form-control" id="adjust_time" placeholder="调整时间" name='adjust_time' value="{{ old('adjust_time') }}">
        </div>
    </div>
    <a href='javascript:' class='btn btn-info col-sm-12'>sku1</a>
    <div>
        <div class='row'>
            <div class="form-group col-sm-6">
                <label for="item_id" class='control-label'>item号</label> 
                <input type='text' class="form-control item_id" id="arr[item_id][0]" placeholder="item号" name='arr[item_id][0]' value="{{ old('arr[item_id][0]') }}" readonly>
            </div>
            <div class="form-group col-sm-6">
                <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input type='text' class="form-control sku" id="arr[sku][0]" placeholder="sku" name='arr[sku][0]' value="{{ old('arr[sku][0]') }}">
            </div>
        </div>
        <div class='row'>
            <div class='form-group col-sm-3'>
                <label>出入库类型</label>
                <div class='radio type'>
                    <label>
                        <input type='radio' name='arr[type][0]' value='入库' {{ old('arr[type][0]') ? old('arr[type][0]') == '入库' ? 'checked' : '' : 'checked'}}>入库
                    </label>
                </div>
                <div class='radio type'>
                    <label>
                        <input type='radio' name='arr[type][0]' value='出库' {{ old('arr[type][0]') ? old('arr[type][0]') == '入库' ? 'checked' : '' : ''}}>出库
                    </label>
                </div>
            </div>
            <div class="form-group col-sm-3">
                <label for="warehouse_positions_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name='arr[warehouse_positions_id][0]' id='arr[warehouse_positions_id][0]' class='form-control warehouse_positions_id'>
                    <option>请选择库位</option>
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label for="amount" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input type='text' class="form-control amount" id="arr[amount][0]" placeholder="数量" name='arr[amount][0]' value="{{ old('arr[amount][0]') }}">
            </div>
            <div class="form-group col-sm-3">
                <label for="total_amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input type='text' class="form-control total_amount" id="arr[total_amount][0]" placeholder="总金额" name='arr[total_amount][0]' value="{{ old('arr[total_amount][0]') }}" readonly>
            </div>
        </div>
        <div class='form-group'>
            <label for='label'>备注(原因)</label>
            <textarea class='form-control remark' name='arr[remark][]' id='arr[remark][0]'>{{ old('arr[remark][0]') }}</textarea>
        </div>
    </div>
    <div class='form-group'>
        <a href='javascript:' class='btn btn-info col-sm-12' id='create_form'>
            <span class='glyphicon glyphicon-plus'>新增</span>
        </a>
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        var current = 1;
        $('#create_form').click(function(){
              $('#create_form').before("<div class='form-group append'></div>");
              var appendhtml = "\
    <a href='javascript:' class='btn btn-info col-sm-10'>sku"+(current+1)+"</a><a href='javascript:' class='btn btn-info div_del col-sm-1'><span class='glyphicon glyphicon-remove'></span></a>\
    <div>\
        <div class='row'>\
            <div class='form-group col-sm-6'>\
                <label for='item_id' class='control-label'>item号</label> \
                <input type='text' class='form-control item_id' id='arr[item_id]["+current+"]' placeholder='item号' name='arr[item_id]["+current+"]' value='{{ old('arr[item_id]["+current+"]') }}' readonly>\
            </div>\
            <div class='form-group col-sm-6'>\
                <label for='sku' class='control-label'>sku</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                <input type='text' class='form-control sku' id='arr[sku]["+current+"]' placeholder='sku' name='arr[sku]["+current+"]' value='{{ old('arr[sku]["+current+"]') }}'>\
            </div>\
        </div>\
        <div class='row'>\
            <div class='form-group col-sm-3'>\
                <label>出入库类型</label>\
                <div class='radio type'>\
                    <label>\
                        <input type='radio' name='arr[type]["+current+"]' value='入库' {{ old('arr[type]["+current+"]') ? old('arr[type]["+current+"]') == '入库' ? 'checked' : '' : 'checked'}}>入库\
                    </label>\
                </div>\
                <div class='radio type'>\
                    <label>\
                        <input type='radio' name='arr[type]["+current+"]' value='出库' {{ old('arr[type]["+current+"]') ? old('arr[type]["+current+"]') == '入库' ? 'checked' : '' : ''}}>出库\
                    </label>\
                </div>\
            </div>\
            <div class='form-group col-sm-3'>\
                <label for='warehouse_positions_id'>库位</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                <select name='arr[warehouse_positions_id]["+current+"]' id='arr[warehouse_positions_id]["+current+"]' class='form-control warehouse_positions_id'></select>\
            </div>\
            <div class='form-group col-sm-3'>\
                <label for='amount' class='control-label'>数量</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                <input type='text' class='form-control amount' id='arr[amount]["+current+"]' placeholder='数量' name='arr[amount]["+current+"]' value='{{ old('arr[amount]["+current+"]') }}'>\
            </div>\
            <div class='form-group col-sm-3'>\
                <label for='total_amount' class='control-label'>总金额(￥)</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                <input type='text' class='form-control total_amount' id='arr[total_amount]["+current+"]' placeholder='总金额' name='arr[total_amount]["+current+"]' value='{{ old('arr[total_amount]["+current+"]') }}' readonly>\
            </div>\
        </div>\
        <div class='form-group'>\
            <label for='label'>备注(原因)</label>\
            <textarea class='form-control remark' name='arr[remark]["+current+"]' id='arr[remark]["+current+"]'>{{ old('arr[remark]["+current+"]') }}</textarea>\
        </div>\
    </div>";
            $('.append:last').html(appendhtml);

            val = $('#warehouses_id').val();
            $.ajax({
                url: "{{ route('getposition') }}",
                data: {val:val},
                dataType:'json',
                type:'get',
                success:function(result){
                    $('.warehouse_positions_id').empty();
                    for(var i=0;i<result.length;i++)
                        $('<option value='+result[i]['id']+'>'+result[i]['name']+'</option>').appendTo($('.warehouse_positions_id'));
                }
            });

            current++;
        });

        $(document).on('blur', '.amount', function(){
            var sku = $(this).parent().parent().prev().find('.sku').val();
            var tmp = $(this);
            if(tmp.val()) {
                $.ajax({
                    url:"{{ route('getunitcost') }}",
                    data:{sku:sku},
                    dataType:'json',
                    'type':'get',
                    success:function(result){
                        tmp.parent().next().children('.total_amount').val(result*tmp.val());
                    }
                });
            }
        });

        $(document).on('click','.div_del',function(){
            $(this).parent().parent().remove(); 
        });

        $(document).on('blur', '.sku', function(){
            var tmp = $(this);
            var sku_val = $(this).val();
            var test = $(this).val();
            if(sku_val){
            $.ajax({
                url: "{{route('getitemid')}}",
                data: {sku_val:sku_val},
                dataType: 'json',
                type: 'get',
                success: function(result){
                    tmp.parent().prev().children(':text').val(result);
                    if(!result) {
                        $('#sku').val('');
                        alert('sku不存在');
                    }
                } 
            });
            }
            
        });

        $(document).on('change', '#warehouses_id', function(){
            val = $(this).val();
            $.ajax({
                url: "{{ route('getposition') }}",
                data: {val:val},
                dataType:'json',
                type:'get',
                success:function(result){
                    $('.warehouse_positions_id').empty();
                    for(var i=0;i<result.length;i++)
                        $('<option value='+result[i]['id']+'>'+result[i]['name']+'</option>').appendTo($('.warehouse_positions_id'));
                }
            });
        });
        
        $('#adjust_time').cxCalendar();
        $('#check_time').cxCalendar();
    });
</script>